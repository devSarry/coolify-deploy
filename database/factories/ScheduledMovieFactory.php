<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\MovieProgram;
use App\Models\ScheduledMovie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScheduledMovieFactory extends Factory
{
    protected $model = ScheduledMovie::class;

    public function definition(): array
    {
        return [
            'scheduled_time' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'movie_program_id' => MovieProgram::factory(),
            'movie_id' => Movie::factory(),
        ];
    }
}
