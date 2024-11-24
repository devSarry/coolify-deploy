<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('/poster', 'poster' );

Volt::route('/movies', 'pages.movies.movieprogram')
    ->name('movies');

Volt::route('/movies/{id}', 'pages.movies.movieprogram')
    ->name('public-movie-program');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
