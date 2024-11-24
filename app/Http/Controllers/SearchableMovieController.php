<?php

namespace App\Http\Controllers;

use App\Models\SearchableMovie;
use Illuminate\Http\Request;

class SearchableMovieController extends Controller
{
    public function index()
    {
        return SearchableMovie::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'imdb_id' => ['required'],
            'title_type' => ['nullable'],
            'primary_title' => ['nullable'],
            'original_title' => ['nullable'],
            'original_language' => ['nullable'],
            'release_date' => ['nullable', 'date'],
            'runtime_minutes' => ['nullable', 'integer'],
            'genres' => ['nullable'],
            'imdb_rating' => ['nullable'],
            'imdb_votes' => ['nullable', 'integer'],
            'vote_average' => ['nullable'],
            'vote_count' => ['nullable', 'integer'],
            'poster_path' => ['nullable'],
            'cast' => ['nullable'],
            'movie_genre_id' => ['required', 'exists:movie_genres'],
        ]);

        return SearchableMovie::create($data);
    }

    public function show(SearchableMovie $searchableMovie)
    {
        return $searchableMovie;
    }

    public function update(Request $request, SearchableMovie $searchableMovie)
    {
        $data = $request->validate([
            'imdb_id' => ['required'],
            'title_type' => ['nullable'],
            'primary_title' => ['nullable'],
            'original_title' => ['nullable'],
            'original_language' => ['nullable'],
            'release_date' => ['nullable', 'date'],
            'runtime_minutes' => ['nullable', 'integer'],
            'genres' => ['nullable'],
            'imdb_rating' => ['nullable'],
            'imdb_votes' => ['nullable', 'integer'],
            'vote_average' => ['nullable'],
            'vote_count' => ['nullable', 'integer'],
            'poster_path' => ['nullable'],
            'cast' => ['nullable'],
            'movie_genre_id' => ['required', 'exists:movie_genres'],
        ]);

        $searchableMovie->update($data);

        return $searchableMovie;
    }

    public function destroy(SearchableMovie $searchableMovie)
    {
        $searchableMovie->delete();

        return response()->json();
    }
}
