<?php

use App\Types\MovieResult;
use App\Models\SearchableMovie;
use function Livewire\Volt\{state, on};


/** @var MovieResult[]|null $results */
state(['movie_search' => '', 'results' => null, 'modal' => false, 'selected_movie' => null]);


$movieSearch = function () {
    $this->searching = null;
    $this->selected_movie = null;
    $this->results= SearchableMovie::search($this->movie_search)->take(25)->get();
};

$setSelectedMovie = function (SearchableMovie $movie) {
    $this->selected_movie = $movie;
};

on(['back-to-movies-search' => function () {
    $this->selected_movie = null;
}]);

?>

<div>
    <x-mary-modal wire:model="modal" >
        <x-mary-form wire:submit="movieSearch" no-separator>

            <div class="grid grid-cols-4 gap-4 pt-4">
                <div class="col-span-3" >
                    <x-mary-input
                        autocomplete="off"
                        wire:model="movie_search"
                        wire:keydown="movieSearch"
                        id="movie_search"
                        name="movie_search"
                        type="text"
                        placeholder="{{ __('Search...') }}"
                        icon-right="m-magnifying-glass"
                    />
                </div>


                <x-mary-button
                    label="{{ __('Search') }}"
                    class="btn-primary self-end"
                    type="submit"
                    spinner="movieSearch"
                />

            </div>
        </x-mary-form>
        @if ($results && $selected_movie === null)
            <div class="grid grid-cols-2 gap-4 mt-4 sm:grid-cols-2 md:grid-cols-3">

                @foreach ($results as $index => $result)
                    <div
                        wire:click="setSelectedMovie({{ $result }})"
                        class="opacity-0 translate-y-5 transition-all duration-300"
                        style="animation: fadeIn 300ms forwards; animation-delay: {{ $index * 50 }}ms;"
                    >
                        <x-movie-result-card :result="$result" />
                    </div>
                @endforeach

            </div>
        @endif

        @if ($selected_movie)
            <div wire:transition>
                <livewire:pages.movies.create-movie-program :selected_movie="$selected_movie"/>

            </div>
        @endif

    </x-mary-modal>

    <x-mary-button class="btn-primary" label="Add Movie" @click="$wire.modal = true" />

</div>
