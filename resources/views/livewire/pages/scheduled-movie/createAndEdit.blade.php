<?php

use Carbon\Carbon;
use Tmdb\Model\Movie;
use App\Models\SearchableMovie;
use App\Models\MovieProgram;
use App\Models\ScheduledMovie;
use function Livewire\Volt\{state, mount, uses, layout};

use Mary\Traits\Toast;

# Had a difficult time abstracting a form component from this file. Livewire's reactivity was not working
# as documented. Could be a skill issue.

uses([Toast::class]);

layout('layouts.app');

state(['selected_movie' => null,
    'scheduledMovie' => null,
    'overview' => null,
    'rating' => 2,
    'movieDate' => null,
    'movieTime' => null,
    'formActionLabel' => 'Add to Calendar',
    'dConfig' => [
        "enableTime" => true,
        "noCalendar" => true,
        "dateFormat" => "H:i",
        "time_24hr" => true
    ]
]);

mount(function () {
    $tmdb = new \App\TMDB\WrapperApi();

    if(request()->routeIs('scheduled-move-create')) {
        $this->selected_movie = SearchableMovie::findOrFail(request('id'));
        $this->overview = $tmdb->getMovieDetails($this->selected_movie->tmdb_id)['overview'];
        $this->rating = $this->selected_movie->imdb_rating;
    }


    if(request()->routeIs('scheduled-move-edit')) {
        $scheduledMovie = ScheduledMovie::findOrFail(request('id'));
        $this->scheduledMovie = $scheduledMovie;

        if (!auth()->user()->can('update', $scheduledMovie)) {
            return redirect()->route('home');
        }

        $this->selected_movie = $scheduledMovie->movie;
        $this->overview = $tmdb->getMovieDetails($this->selected_movie->tmdb_id)['overview'];
        $this->rating = $this->selected_movie->imdb_rating;

        $this->movieDate = Carbon::parse($scheduledMovie->scheduled_time);
        $this->movieTime = Carbon::parse($scheduledMovie->scheduled_time)->format('H:i');
        $this->formActionLabel = 'Update';
    }
});

$createMovie = function () {
    if (!auth()->check()) {
        return $this->redirect(route('register'));
    }

    $date = Carbon::parse($this->movieDate)->format('Y-m-d');
    $datetime = Carbon::parse($date . ' ' . $this->movieTime)->toDateTimeString();

    auth()->user()->scheduledMovies()->create([
        'movie_program_id' => auth()->user()->getDefaultMovieProgramId(),
        'movie_id' => $this->selected_movie->id,
        'scheduled_time' => $datetime
    ]);

    $this->success('Movie added to calendar');

    $this->redirect('/program');
};

$updateMovie = function () {
    if (!auth()->user()->can('update', $this->scheduledMovie)) {
        return redirect()->route('home');
    }

    $date = Carbon::parse($this->movieDate)->format('Y-m-d');
    $datetime = Carbon::parse($date . ' ' . $this->movieTime)->toDateTimeString();

    $this->scheduledMovie->update(['scheduled_time' => $datetime]);

    $this->success('Movie schedule updated');
};

?>
<section class="py-4 md:py-8">
    <div class="flex flex-col">
        <h1 class="text-2xl font-bold my-4">{{ $this->selected_movie->primary_title }}</h1>

        <!-- Change to stack on mobile, side-by-side on larger screens -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Poster section -->
            <div class="col-span-1">
                <!-- Make image width responsive -->
                <div class="max-w-[300px] mx-auto md:mx-0">
                    <img
                        src="{{ $this->selected_movie->poster_url }}"
                        alt="{{ $this->selected_movie->primary_title }}"
                        class="w-full h-auto rounded-lg shadow-lg"
                    />
                    <a href="{{ $this->selected_movie->imdb_url }}" target="_blank" class="block">
                        <x-mary-badge class="badge-primary mt-4 py-4 w-full">
                            <x-slot:value>
                                <x-mary-icon class="h-6 mr-2" name="fab.imdb" /> {{ $rating }}
                            </x-slot:value>
                        </x-mary-badge>
                    </a>
                </div>
            </div>

            <!-- Details section -->
            <div class="col-span-1 md:col-span-3">
                <!-- Add proper text wrapping and spacing -->
                <div class="prose max-w-none mb-6">
                    {{ $this->overview }}
                </div>

                <!-- Form section -->
                <form wire:submit="{{ request()->routeIs('scheduled-move-edit') ? 'updateMovie' : 'createMovie' }}" class="space-y-4 max-w-2xl">
                    <p class="text-lg font-bold">Add Movie To Calendar</p>

                    <!-- Make inputs stack on mobile -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/2">
                            <x-mary-datepicker
                                placeholder="DD/MM/YY"
                                wire:model="movieDate"
                                icon="o-calendar"
                                class="w-full"
                            />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <x-mary-datepicker
                                placeholder="00:00"
                                wire:model="movieTime"
                                icon="o-clock"
                                :config="$dConfig"
                                class="w-full"
                            />
                        </div>
                        <x-mary-button
                            type="submit"
                            :label="$formActionLabel"
                            class="btn-primary w-full sm:w-auto"
                            spinner="save"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
