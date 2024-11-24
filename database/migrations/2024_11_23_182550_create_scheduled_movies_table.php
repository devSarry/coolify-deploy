<?php

use App\Models\Movie;
use App\Models\MovieProgram;
use App\Models\SearchableMovie;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scheduled_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(MovieProgram::class);
            $table->foreignIdFor(SearchableMovie::class, 'movie_id');
            $table->dateTime('scheduled_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_movies');
    }
};
