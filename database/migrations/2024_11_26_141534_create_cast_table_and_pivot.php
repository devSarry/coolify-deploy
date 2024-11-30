<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cast_members', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('searchable_movie_cast', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('searchable_movie_id'); // Links to searchable_movies
            $table->unsignedBigInteger('cast_member_id');
            $table->index(['searchable_movie_id', 'cast_member_id'], 'searchable_movie_id_cast_member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cast_members');
        Schema::dropIfExists('searchable_movie_cast');
    }
};
