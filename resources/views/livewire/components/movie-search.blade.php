<?php

use App\Types\MovieResult;
use App\Models\SearchableMovie;
use function Livewire\Volt\{state, on};


/** @var MovieResult[]|null $results */
state(['movie_search' => '', 'results' => null, 'modal' => false, 'selected_movie' => null, 'show_results' => false]);


$movieSearch = function () {
    $this->show_results = true;
    $this->selected_movie = null;
    $this->results = SearchableMovie::search($this->movie_search)
        ->options([
            "query_by" => "primary_title,original_title",
            ])
        ->take(25)
        ->get();
};

$setSelectedMovie = function (SearchableMovie $movie) {
    $this->selected_movie = $movie;

};

on(['back-to-movies-search' => function () {
    $this->selected_movie = null;
}]);

on(['hide-movie-search' => function () {
    $this->show_results = false;
}]);

?>

<div x-data="{ inputActive: false }" class="relative">
    {{-- Search Bar --}}
    <x-mary-form wire:submit.prevent="movieSearch">
        <x-mary-input

            @focus="inputActive = true"
            @blur="setTimeout(() => inputActive = false, 200)"
            label="{{ __('Search') }}"
            wire:model="movie_search"
            wire:keydown.debounce.300ms="movieSearch"
            id="search_movies"
            name="search_movies"
            type="text"
            placeholder="{{ __('Search for a movie...') }}"
            icon-left="o-magnifying-glass"
            class="w-full"
        />
    </x-mary-form>

    {{-- Search Results --}}
    @if ($results)
        <div class="absolute z-50 bg-brand-100 border rounded-lg shadow-md w-full max-h-80 overflow-y-auto mt-2"
             x-show="inputActive"
             x-cloak>
            <ul>
                @foreach ($results as $movie)
                    <li
                        class="flex items-center gap-4 p-4 border-b last:border-b-0 hover:bg-gray-100 cursor-pointer"
                        wire:click="setSelectedMovie({{ $movie }})"

                    >
                        <a href="movie/{{ $movie->id }}" wire:navigate.hover>
                            {{-- Poster --}}
                            <img
                                src="{{ $movie->poster_url }}"
                                alt="{{ $movie->primary_title }} Poster"
                                class="w-12 h-16 object-cover rounded"
                            />
                            {{-- Movie Info --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $movie->primary_title }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $movie->release_date ? $movie->release_date->format('Y') : 'N/A' }}
                                </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
