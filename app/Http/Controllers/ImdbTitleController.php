<?php

namespace App\Http\Controllers;

use App\Models\ImdbTitle;
use Illuminate\Http\Request;

class ImdbTitleController extends Controller
{
    public function index()
    {
        return ImdbTitle::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tconst' => ['required'],
            'title_type' => ['nullable'],
            'primary_title' => ['nullable'],
            'original_title' => ['nullable'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'runtime_minutes' => ['nullable', 'integer'],
            'parent_tconst' => ['nullable'],
            'season_number' => ['nullable', 'integer'],
            'episode_number' => ['nullable', 'integer'],
            'genres' => ['nullable'],
            'average_rating' => ['nullable'],
            'num_votes' => ['nullable', 'integer'],
        ]);

        return ImdbTitle::create($data);
    }

    public function show(ImdbTitle $imdbTitle)
    {
        return $imdbTitle;
    }

    public function update(Request $request, ImdbTitle $imdbTitle)
    {
        $data = $request->validate([
            'tconst' => ['required'],
            'title_type' => ['nullable'],
            'primary_title' => ['nullable'],
            'original_title' => ['nullable'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'runtime_minutes' => ['nullable', 'integer'],
            'parent_tconst' => ['nullable'],
            'season_number' => ['nullable', 'integer'],
            'episode_number' => ['nullable', 'integer'],
            'genres' => ['nullable'],
            'average_rating' => ['nullable'],
            'num_votes' => ['nullable', 'integer'],
        ]);

        $imdbTitle->update($data);

        return $imdbTitle;
    }

    public function destroy(ImdbTitle $imdbTitle)
    {
        $imdbTitle->delete();

        return response()->json();
    }
}
