<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('/poster', 'poster' );

Volt::route('/program', 'pages.movies.movieprogram')
    ->name('program');

Volt::route('/program/{id}', 'pages.movies.movieprogram')
    ->name('public-movie-program');

Volt::route('/movie/{id}', 'pages.movies.create-movie-program')
    ->name('public-movie');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
