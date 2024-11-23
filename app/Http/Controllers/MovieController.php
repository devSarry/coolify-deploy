<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'year' => ['required', 'integer'],
            'imdb_url' => ['required'],
            'rating' => ['nullable', 'numeric'],
        ]);

        return Movie::create($data);
    }

    public function show(Movie $movie)
    {
        return $movie;
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'year' => ['required', 'integer'],
            'imdb_url' => ['required'],
            'rating' => ['nullable', 'numeric'],
        ]);

        $movie->update($data);

        return $movie;
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json();
    }
}
