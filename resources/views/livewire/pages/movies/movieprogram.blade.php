<?php


use App\Models\ScheduledMovie;
use App\Models\SearchableMovie;
use App\Models\MovieProgram;
use function Livewire\Volt\{state, layout, mount};

/** @var ScheduledMovie[]|null $results */
state(['scheduled_movies' => null]);

layout('layouts.app');

mount(function () {
    if (!auth()->check() && request()->routeIs('public-movie-program')) {
        $this->scheduled_movies = MovieProgram::where('hash_id', request('id'))
            ->firstOr(fn() => redirect()->route('home'))
            ->scheduledMovies()
            ->with('movie')
            ->orderBy('scheduled_time')
            ->get();
    } else {
        $this->scheduled_movies = auth()->user()
            ->scheduledMovies()
            ->with('movie')
            ->orderBy('scheduled_time')
            ->get();
    }
});

?>

<div class="container mx-auto">
    <x-mary-header title="Movie Program"/>

    <x-mary-header size="text-inherit" separator progress-indicator>
        {{-- SEARCH --}}
        <x-slot:title>
            <x-mary-input placeholder="Search ..." icon="o-magnifying-glass">
            </x-mary-input>
        </x-slot:title>

        {{-- SORT --}}
        <x-slot:actions>
            <livewire:components.movie-search/>
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card>
        @forelse($scheduled_movies as $scheduledMovie)
            <x-mary-list-item  :item="$scheduledMovie">
                <x-slot:avatar>
                    <img src="{{ $scheduledMovie->movie->poster_url }}" alt="{{ $scheduledMovie->movie->primary_title }}"
                         class="h-20 rounded-lg"/>
                </x-slot:avatar>
                <x-slot:value>
                    <div class="flex flex-row justify-between gap-4">
                        <div class="flex-1 text-wrap w-24 text-lg font-bold ">{{ $scheduledMovie->movie->primary_title }}</div>
                        <div class="flex text-center text-wrap">
                            {{ $scheduledMovie->formattedScheduledTime() }}
                        </div>

                        <div class="flex flex-col gap-2">
                            @if(@auth()->user())
                                <x-mary-button icon="o-trash" class="btn-error" wire:click="removeMovie({{ $scheduledMovie->id }})"/>
                                <x-mary-button icon="o-pencil-square" class="btn-neutral" wire:click="editMovie({{ $scheduledMovie->id }})"/>
                            @endif
                        </div>
                    </div>
                </x-slot:value>
            </x-mary-list-item>
        @empty
            <div class="text-center">
                <p class="text-lg">No movies scheduled</p>
            </div>
        @endforelse
    </x-mary-card>


</div>
