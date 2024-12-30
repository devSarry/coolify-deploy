<?php

use App\Models\ScheduledMovie;
use App\Models\SearchableMovie;
use App\Models\MovieProgram;
use Mary\Traits\Toast;
use function Livewire\Volt\{state, layout, mount, uses};

uses(Toast::class);

/** @var ScheduledMovie[]|null $results */
state([
    'scheduled_movies' => null,
    'showDeleteModal' => false,
    'next_movie' => null,
]);

layout('layouts.app');

mount(function () {
    $query = auth()->check() ? auth()->user()->scheduledMovies() : MovieProgram::where('hash_id', request('id'))->firstOr(fn() => redirect()->route('home'))->scheduledMovies();

    $this->scheduled_movies = (clone $query)
        ->with('movie')
        ->orderBy('scheduled_time', 'desc')
        ->get();

    $this->next_movie = (clone $query)
        ->with('movie')
        ->where('scheduled_time', '>=', now())
        ->orderBy('scheduled_time')
        ->first();
});

$removeMovie = function (ScheduledMovie $scheduledMovie) {
    if (!auth()->user()->can('delete', $scheduledMovie)) {
        abort(403, 'Unauthorized action.');
    }
    if ($scheduledMovie->delete()) {
        $this->scheduled_movies = auth()->user()
            ->scheduledMovies()
            ->with('movie')
            ->orderBy('scheduled_time', 'desc') // Change order back to desc
            ->get();

        $this->next_movie = auth()->user()
            ->scheduledMovies()
            ->with('movie')
            ->where('scheduled_time', '>=', now())
            ->orderBy('scheduled_time')
            ->first();

        $this->showDeleteModal = false;
        $this->success(
            title: 'Movie Removed',
            description: 'The movie was successfully removed from your program'
        );
    } else {
        // Show error toast
        $this->error(
            title: 'Error',
            description: 'Failed to remove the movie from your program'
        );
    }
};

?>

<div class="container mx-auto py-8 px-2">
    <x-mary-header title="Movie Program" class="mb-8">
        <x-slot:subtitle>
            Manage your scheduled movies and showtimes
        </x-slot:subtitle>
    </x-mary-header>

    <div class="mb-8">
        <livewire:components.movie-search/>
    </div>

    @if($this->next_movie)
        <x-mary-card class="bg-base-100 mb-8">
            <x-slot:title class="font-bold text-lg">
                <x-mary-icon name="o-star" class="w-5 h-5 text-yellow-400 inline-block"/>
                Up Next
            </x-slot:title>
            <a href="{{ route('scheduled-move-create', $this->next_movie->movie_id ) }}">
                <div class="flex w-full py-2 gap-4">
                    <div class="shrink-0">
                        <img src="{{ $this->next_movie->movie->poster_url }}"
                             alt="{{ $this->next_movie->movie->primary_title }}"
                             class="h-32 w-24 object-cover rounded-lg shadow-md"
                             loading="lazy"/>
                    </div>
                    <div class="flex-1 flex flex-col justify-between md:justify-center min-w-0">
                        <h3 class="text-lg font-bold text-wrap text-neutral-content">
                            {{ $this->next_movie->movie->primary_title }}
                        </h3>
                        <div class=" gap-2 text-gray-400 py-2">
                            <x-mary-icon name="o-clock" class="w-5"/>
                            <span class="text-sm text-wrap">{{ $this->next_movie->formattedScheduledTime() }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </x-mary-card>
    @endif

    <x-mary-card class="bg-base-100">
        @php
            $pastMovies = false;
        @endphp
        @forelse($scheduled_movies as $scheduledMovie)
            @if (!$pastMovies && $scheduledMovie->scheduled_time < now())
                @php
                    $pastMovies = true;
                @endphp
                <hr class="my-4 border-t border-neutral-content/50">
                <div class="text-center py-4 text-neutral-content text-lg font-semibold">Past Movies</div>
                <hr class="my-4 border-t border-neutral-content/50">
            @endif
            <a href="{{ route('scheduled-move-create', $scheduledMovie->movie_id ) }}">
                <x-mary-list-item :item="$scheduledMovie">
                    <x-slot:value>
                        <div class="flex w-full py-2 gap-4 ">
                            <div class="shrink-0 {{ $scheduledMovie->scheduled_time < now() ? 'opacity-60' : '' }}">
                                <img src="{{ $scheduledMovie->movie->poster_url }}"
                                     alt="{{ $scheduledMovie->movie->primary_title }}"
                                     class="h-32 w-24 object-cover rounded-lg shadow-md"
                                     loading="lazy"/>
                            </div>

                            <div class="flex-1 flex flex-col justify-between md:justify-center min-w-0 {{ $scheduledMovie->scheduled_time < now() ? 'opacity-60' : '' }}">
                                <h3 class="text-lg font-bold text-wrap text-neutral-content">
                                    {{ $scheduledMovie->movie->primary_title }}
                                </h3>

                                <div class=" gap-2 text-gray-400 py-2 {{ $scheduledMovie->scheduled_time < now() ? 'opacity-60' : '' }}">
                                    <x-mary-icon name="o-clock" class="w-5"/>
                                    <span class="text-sm text-wrap">{{ $scheduledMovie->formattedScheduledTime() }}</span>
                                </div>

                                @auth
                                    <div class="flex gap-2 md:hidden">
                                        <x-mary-button size="sm" icon="o-pencil-square" class="btn-neutral"
                                                       link="{{ route('scheduled-move-edit', $scheduledMovie->id) }}"
                                                       title="Edit Movie"/>
                                        <x-mary-button size="sm" icon="o-trash" class="btn-error"
                                                       @click="$wire.showDeleteModal = true; selectedMovie = { id: {{ $scheduledMovie->id }}, title: '{{ $scheduledMovie->movie->primary_title }}' }"/>
                                    </div>
                                @endauth
                            </div>

                            @auth
                                <div class="hidden md:flex flex-col gap-2 justify-center {{ $scheduledMovie->scheduled_time < now() ? 'opacity-60' : '' }}">
                                    <x-mary-button size="sm" icon="o-pencil-square" class="btn-neutral"
                                                   link="{{ route('scheduled-move-edit', $scheduledMovie->id) }}"
                                                   title="Edit Movie"/>
                                    <x-mary-button size="sm" icon="o-trash" class="btn-error w-full"
                                                   @click="$wire.showDeleteModal = true; selectedMovie = { id: {{ $scheduledMovie->id }}, title: '{{ $scheduledMovie->movie->primary_title }}' }"/>
                                </div>

                                <x-mary-modal wire:model="showDeleteModal" class="backdrop-blur">
                                    <div class="p-6">
                                        <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                                        <p class="mb-6">
                                            Are you sure you want to remove <strong>{{ $scheduledMovie->movie->primary_title }}</strong> from your program?
                                        </p>
                                        <div class="flex justify-end gap-3">
                                            <x-mary-button class="btn-neutral" label="Cancel"
                                                           @click="$wire.showDeleteModal = false"/>
                                            <x-mary-button class="btn-error" label="Delete"
                                                           @click="$wire.removeMovie(selectedMovie.id); selectedMovie = null; $wire.showDeleteModal = false;"/>
                                        </div>
                                    </div>
                                </x-mary-modal>
                            @endauth
                        </div>
                    </x-slot:value>
                </x-mary-list-item>
            </a>
        @empty
            <div class="py-12 text-center">
                <x-mary-icon name="o-film" class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                <p class="text-xl text-gray-600">No movies scheduled</p>
                <p class="text-gray-500 mt-2">Use the search above to add movies to your program</p>
            </div>
        @endforelse
    </x-mary-card>
</div>

<script>
    let selectedMovie = null;
</script>
