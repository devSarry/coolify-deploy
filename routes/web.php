<?php

use App\Models\ScheduledMovie;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome')
    ->name('home');

Route::view('/poster', 'poster' );

Volt::route('/program', 'pages.movies.movieprogram')
    ->name('program');

Volt::route('/program/{id}', 'pages.movies.movieprogram')
    ->name('public-movie-program');

Volt::route('/movie/{id}', 'pages.scheduled-movie.createAndEdit')
    ->name('scheduled-move-create');

Volt::route('/scheduled-move/{id}/edit', 'pages.scheduled-movie.createAndEdit')
    ->middleware(['auth', 'verified'])
    ->name('scheduled-move-edit');






Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
