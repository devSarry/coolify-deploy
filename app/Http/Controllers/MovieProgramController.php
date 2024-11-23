<?php

namespace App\Http\Controllers;

use App\Models\MovieProgram;
use Illuminate\Http\Request;

class MovieProgramController extends Controller
{
    public function index()
    {
        return MovieProgram::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
        ]);

        return MovieProgram::create($data);
    }

    public function show(MovieProgram $movieProgram)
    {
        return $movieProgram;
    }

    public function update(Request $request, MovieProgram $movieProgram)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
        ]);

        $movieProgram->update($data);

        return $movieProgram;
    }

    public function destroy(MovieProgram $movieProgram)
    {
        $movieProgram->delete();

        return response()->json();
    }
}
