<?php

namespace App\Console\Commands;

use App\Models\CastMember;
use App\Models\MovieGenre;
use App\Models\ImdbTitle;
use App\Models\SearchableMovie;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImdbImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:imdb-import';

    /**
     * The console command description.
     *
     * @var string
     */
    public function handle()
    {
        $this->importTitles();

        return 0;
    }


    /**
     * Import titles from the extracted CSV file
     */
    protected function importTitles()
    {
        $limit = 0; // Limit the number of records to import
        $rowCount = 0;
        $csvPath = storage_path('app/private/TMDB_all_movies.csv'); // Replace with correct filename
        if (!file_exists($csvPath)) {
            $this->error("CSV file not found: $csvPath");
            exit(1);
        }

        $batchSize = 1000;
        $titlesBatch = [];
        $genresBatch = [];
        $castMembersBatch = [];
        $cachedGenres = MovieGenre::pluck('id', 'name')->toArray(); // Cache existing genres
        $cachedCastMembers = CastMember::pluck('id', 'name')->toArray();


        $totalRows = $this->getLineCountOfDownload($csvPath);
        $bar = $this->output->createProgressBar($totalRows/$batchSize);
        $bar->start();


        // Open and read the CSV file
        if (($handle = fopen($csvPath, 'r')) !== false) {
            $headings = fgetcsv($handle); // Read the first line as column headers
            if (!$headings) {
                $this->error("Failed to read the CSV file headers.");
                exit(1);
            }

            while (($row = fgetcsv($handle)) !== false) {
                if(count($row) != count($headings)) {
                    continue;
                }
                $data = array_combine($headings, $row);

                // Process genres
                $genres = Str::of($data['genres'])->trim()
                    ->explode(',')
                    ->map(fn($item) => trim($item))
                    ->filter()
                    ->toArray();


                foreach ($genres as $genre) {
                    if (!isset($cachedGenres[$genre])) {
                        $genresBatch[$genre] = ['name' => $genre]; // Use the genre name as the key to ensure uniqueness
                    }
                }

                // Process cast members
                $castMembers = Str::of($data['cast'])->trim()
                    ->explode(',')
                    ->map(fn($item) => trim($item))
                    ->filter()
                    ->toArray();

                foreach ($castMembers as $castMember) {
                    if (!isset($cachedCastMembers[$castMember])) {
                        $castMembersBatch[$castMember] = ['name' => $castMember]; // Use the cast name as the key to ensure uniqueness
                    }
                }
                $titlesBatch[] = [
                    'imdb_id' => $this->handleValue($data['imdb_id']),
                    'tmdb_id' => (int)$this->handleValue($data['id']),
                    'tagline' => $this->handleValue($data['tagline']),
                    'primary_title' => $this->handleValue($data['title']),
                    'original_title' => $this->handleValue($data['original_title']),
                    'original_language' => $this->handleValue($data['original_language']),
                    'release_date' => $this->handleDate($data['release_date']),
                    'runtime_minutes' => (int)$data['runtime'] ?: null,
                    'genres' => implode(',', $genres),
                    'imdb_rating' => (float)$data['imdb_rating'] ?: null,
                    'imdb_votes' => (int)$data['imdb_votes'] ?: null,
                    'vote_average' => (float)$data['vote_average'] ?: null,
                    'vote_count' => (int)$data['vote_count'] ?: null,
                    'poster_path' => $this->handleValue($data['poster_path']),
                    'cast' => $this->handleValue($data['cast']),
                ];

                // Stop if limit is reached
                if ($limit && $rowCount >= $limit) {
                    break;
                }

                if (count($titlesBatch) >= $batchSize) {
                    $this->insertBatches($titlesBatch, $genresBatch, $cachedGenres, $castMembersBatch, $cachedCastMembers);
                    $titlesBatch = [];
                    $bar->advance();
                }
            }

            fclose($handle);
        }

        // Insert remaining records
        if (count($titlesBatch) > 0) {
            $this->insertBatches($titlesBatch, $genresBatch, $cachedGenres, $castMembersBatch, $cachedCastMembers);
        }

        $bar->finish();
    }

    private function insertBatches(&$titlesBatch, &$genresBatch, &$cachedGenres,  &$castMembersBatch, &$cachedCastMembers)
    {
        DB::transaction(function () use (&$titlesBatch, &$genresBatch, &$cachedGenres, &$castMembersBatch,  &$cachedCastMembers) {
            if (!empty($genresBatch)) {
                // insert only unique genres not already in the database or in the cached genres
                $genresBatch = array_filter($genresBatch, fn($genre) => !isset($cachedGenres[$genre['name']])); // Remove genres already in the database

                MovieGenre::insertOrIgnore($genresBatch); // Bulk insert genres
                $cachedGenres += MovieGenre::pluck('id', 'name')->toArray(); // Refresh cache
                $genresBatch = [];
            }

            if (!empty($castMembersBatch)) {
                CastMember::insertOrIgnore($castMembersBatch); // Bulk insert cast members
                $cachedCastMembers += CastMember::pluck('id', 'name')->toArray(); // Refresh cache
                $castMembersBatch = [];
            }

            SearchableMovie::upsert($titlesBatch, ['tmdb_id'], array_keys($titlesBatch[0])); // Bulk upsert titles

            foreach ($titlesBatch as $titleData) {
                $movie = SearchableMovie::where('tmdb_id', $titleData['tmdb_id'])->first();

                // Sync genres
                $genreIds = [];
                foreach (explode(',', $titleData['genres']) as $genreName) {
                    if (isset($cachedGenres[$genreName])) {
                        $genreIds[] = $cachedGenres[$genreName];
                    }
                }
                $movie->genres()->sync($genreIds);

                // Sync cast members
                $castIds = [];
                foreach (explode(',', $titleData['cast']) as $castName) {
                    if (isset($cachedCastMembers[$castName])) {
                        $castIds[] = $cachedCastMembers[$castName];
                    }
                }
                $movie->castMembers()->sync($castIds);
            }
        });
    }
    public function getLineCountOfDownload($filename)
    {
        $command = sprintf('cat %s | cat | wc -l', $filename);

        $lineCount = exec($command);

        return (int)$lineCount;
    }

    public function handleValue($value)
    {
        if (Str::of($value)->startsWith('\N')) {
            return null;
        }

        $value = (string)Str::of($value)->trim();
        $value = (string)Str::limit($value, 252);

        return $value;
    }

    public function hasDownloadFile($imdbFilename)
    {
        return Storage::disk('local')->exists($imdbFilename);
    }

    protected function handleDate($value)
    {
        if (!$value || $value === '\N') {
            return null;
        }

        try {
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract and sanitize data from a CSV field.
     */
    protected function extractData($dataString): array
    {
        if (!$dataString) {
            return [];
        }

        $data = json_decode($dataString, true) ?? [];
        return array_map(fn($item) => trim($item), $data);
    }
}
