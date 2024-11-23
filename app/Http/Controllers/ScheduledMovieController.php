<?php

namespace App\Http\Controllers;

use App\Models\ScheduledMovie;
use Illuminate\Http\Request;

class ScheduledMovieController extends Controller
{
    public function index()
    {
        return ScheduledMovie::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users'],
            'movie_program_id' => ['required', 'exists:movie_programs'],
            'movie_id' => ['required', 'exists:movies'],
            'scheduled_time' => ['required', 'date'],
        ]);

        return ScheduledMovie::create($data);
    }

    public function show(ScheduledMovie $scheduledMovie)
    {
        return $scheduledMovie;
    }

    public function update(Request $request, ScheduledMovie $scheduledMovie)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users'],
            'movie_program_id' => ['required', 'exists:movie_programs'],
            'movie_id' => ['required', 'exists:movies'],
            'scheduled_time' => ['required', 'date'],
        ]);

        $scheduledMovie->update($data);

        return $scheduledMovie;
    }

    public function destroy(ScheduledMovie $scheduledMovie)
    {
        $scheduledMovie->delete();

        return response()->json();
    }
}
