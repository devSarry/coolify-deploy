<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movie_genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->index('name');
        });

        Schema::create('movie_genre_searchable_movie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('searchable_movie_id');
            $table->unsignedBigInteger('movie_genre_id');
            $table->index(['searchable_movie_id', 'movie_genre_id'], 'searchable_movie_id_movie_genre_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_genres');
        Schema::dropIfExists('movie_genre_searchable_movie');
    }
};
