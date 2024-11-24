<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function toSearchableArray()
    {
        return array_merge($this->toArray(), [
            "id" => (string) $this->id,
            "vote_average" => (float) $this->vote_average,
            "imdb_rating" => (float) $this->imdb_rating,
            "vote_count" => (int) $this->vote_count,
            "imdb_votes" => (int) $this->imdb_votes,
            "runtime_minutes" => (int) $this->runtime_minutes,
            "release_date" => $this->release_date ? $this->release_date->timestamp : 0,
            ]);
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
