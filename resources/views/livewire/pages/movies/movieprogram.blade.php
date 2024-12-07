<?php


use App\Models\ScheduledMovie;
use App\Models\SearchableMovie;
use App\Models\MovieProgram;
use Mary\Traits\Toast;
use function Livewire\Volt\{state, layout, mount, uses};

uses(Toast::class);

/** @var ScheduledMovie[]|null $results */
state(['scheduled_movies' => null,
    'showDeleteModal' => false,]);

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

$removeMovie = function (ScheduledMovie $scheduledMovie) {
    if (!auth()->user()->can('delete', $scheduledMovie)) {
        abort(403, 'Unauthorized action.');
    }
    if ($scheduledMovie->delete()) {
        $this->scheduled_movies = auth()->user()
            ->scheduledMovies()
            ->with('movie')
            ->orderBy('scheduled_time')
            ->get();
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

    <x-mary-card class="bg-base-100">
        @forelse($scheduled_movies as $scheduledMovie)
            <x-mary-list-item :item="$scheduledMovie" >
                <x-slot:value>
                    <!-- Main container -->
                    <div class="flex w-full py-2 gap-4">
                        <!-- Movie Poster (Left side) -->
                        <div class="shrink-0">
                            <img src="{{ $scheduledMovie->movie->poster_url }}"
                                 alt="{{ $scheduledMovie->movie->primary_title }}"
                                 class="h-32 w-24 object-cover rounded-lg shadow-md"
                                 loading="lazy"/>
                        </div>

                        <!-- Mobile: Stack everything to the right of poster -->
                        <!-- Desktop: Middle content section -->
                        <div class="flex-1 flex flex-col justify-between md:justify-center min-w-0">
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-wrap text-neutral-content">
                                {{ $scheduledMovie->movie->primary_title }}
                            </h3>

                            <!-- DateTime -->
                            <div class=" gap-2 text-gray-400 py-2">
                                <x-mary-icon name="o-clock" class="w-5"/>
                                <span class="text-sm text-wrap">{{ $scheduledMovie->formattedScheduledTime() }}</span>
                            </div>

                            <!-- Mobile Actions -->
                            @auth
                                <div class="flex gap-2 md:hidden">
                                    <x-mary-button size="sm" icon="o-pencil-square" class="btn-neutral"
                                                   link="{{ route('scheduled-move-edit', $scheduledMovie->id) }}"
                                                   title="Edit Movie"/>
                                    <x-mary-button size="sm" icon="o-trash" class="btn-error"
                                                   @click="$wire.showDeleteModal = true"/>
                                </div>
                            @endauth
                        </div>

                        <!-- Desktop Actions (Right side) -->
                        @auth
                            <div class="hidden md:flex flex-col gap-2 justify-center">
                                <x-mary-button size="sm" icon="o-pencil-square" class="btn-neutral"
                                               link="{{ route('scheduled-move-edit', $scheduledMovie->id) }}"
                                               title="Edit Movie"/>
                                <x-mary-button size="sm" icon="o-trash" class="btn-error w-full"
                                               @click="$wire.showDeleteModal = true"/>
                            </div>

                            <!-- Delete Modal -->
                            <x-mary-modal wire:model="showDeleteModal" class="backdrop-blur">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                                    <p class="mb-6 text-gray-600">
                                        Are you sure you want to remove "{{ $scheduledMovie->movie->primary_title }}" from your program?
                                    </p>
                                    <div class="flex justify-end gap-3">
                                        <x-mary-button class="btn-neutral" label="Cancel"
                                                       @click="$wire.showDeleteModal = false"/>
                                        <x-mary-button class="btn-error" label="Delete"
                                                       @click="$wire.removeMovie({{ $scheduledMovie }})"/>
                                    </div>
                                </div>
                            </x-mary-modal>
                        @endauth
                    </div>
                </x-slot:value>
            </x-mary-list-item>
        @empty
            <div class="py-12 text-center">
                <x-mary-icon name="o-film" class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                <p class="text-xl text-gray-600">No movies scheduled</p>
                <p class="text-gray-500 mt-2">Use the search above to add movies to your program</p>
            </div>
        @endforelse
    </x-mary-card>
</div>
