<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MovieGenre extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name',
    ];

    public function movies()
    {
        return $this->belongsToMany(SearchableMovie::class, 'movie_genre_searchable_movie', 'movie_genre_id', 'searchable_movie_id');
    }
}
