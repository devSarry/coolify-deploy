<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class SearchableMovie extends Model
{
    public $timestamps = false;

    use Searchable;

    protected $fillable = [
        'imdb_id',
        'tmdb_id',
        'tagline',
        'primary_title',
        'original_title',
        'original_language',
        'release_date',
        'runtime_minutes',
        'genres',
        'imdb_rating',
        'imdb_votes',
        'vote_average',
        'vote_count',
        'poster_path',
        'cast',
    ];

    public function movieGenre(): BelongsToMany
    {
        return $this->belongsToMany(MovieGenre::class, 'movie_genre_searchable_movie',  'searchable_movie_id', 'movie_genre_id',);
    }

    public function genres()
    {
        return $this->belongsToMany(MovieGenre::class, 'movie_genre_searchable_movie', 'searchable_movie_id', 'movie_genre_id');    }

    public function castMembers()
    {
        return $this->belongsToMany(CastMember::class, 'searchable_movie_cast', 'searchable_movie_id', 'cast_member_id');
    }

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'vote_average' => 'float',
            'imdb_rating' => 'float',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            "id" => (string) $this->id,
            "tmdb_id" => $this->tmdb_id,
            "imdb_id" => (string) $this->imdb_id,
            "primary_title" => $this->primary_title,
            "original_title" => $this->original_title,
            "vote_count" => (int) $this->vote_count,
            "imdb_votes" => (int) $this->imdb_votes,
            "vote_average" => (float) $this->vote_average ?? 0,
            "imdb_rating" => (float) $this->imdb_rating,
            "poster_path" => $this->posterUrl(),
            "runtime_minutes" => (int) $this->runtime_minutes,
            "tagline" => $this->tagline,
            "original_language" => $this->original_language,
            "genres" => $this->movieGenre->pluck('name')->join(','), // Use movieGenre relationship
            "cast" => $this->castMembers->pluck('name')->join(','), // Use castMembers relationship
            "release_date" => $this->release_date ? $this->release_date->timestamp : 0,
        ];
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn () =>  $this->poster_path ? 'https://image.tmdb.org/t/p/w500' . $this->poster_path : 'https://via.placeholder.com/500x750?text=No+Poster',
        );

    }

    public function imdbUrl() : Attribute
    {

        return Attribute::make(
            get: fn () => $this->imdb_id ? 'https://www.imdb.com/title/' . $this->imdb_id : null,
        );
    }
}
