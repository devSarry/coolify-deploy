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
        Schema::table('movie_programs', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('program_id');
        });

        // Set all existing movie programs to have their public program set to false
        DB::table('movie_programs')->update(['is_public' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_programs', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
