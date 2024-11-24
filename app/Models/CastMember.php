<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CastMember extends Model
{
    protected $fillable = ['name'];

    public function searchableMovies()
    {
        return $this->belongsToMany(SearchableMovie::class, 'searchable_movie_cast', 'cast_member_id', 'searchable_movie_id');
    }
}
