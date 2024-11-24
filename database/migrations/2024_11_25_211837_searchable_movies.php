<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('searchable_movies', function (Blueprint $table) {
            $table->id();
            $table->integer('tmdb_id')->unique();
            $table->string('imdb_id', 10)->nullable();
            $table->string('primary_title', 255)->nullable();
            $table->string('original_title', 255)->nullable();
            $table->text('tagline')->nullable();
            $table->string('original_language', 255)->nullable();
            $table->date('release_date')->nullable();
            $table->smallInteger('runtime_minutes')->nullable();
            $table->string('genres', 255)->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();
            $table->integer('imdb_votes')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->integer('vote_count')->nullable();
            $table->string('poster_path', 255)->nullable();
            $table->text('cast')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('searchable_movies');
    }
};
