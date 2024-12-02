<?php

use Carbon\Carbon;
use Tmdb\Model\Movie;
use App\Models\SearchableMovie;
use App\Models\MovieProgram;
use function Livewire\Volt\{state, mount, uses, layout};

use Mary\Traits\Toast;


uses([Toast::class]);

layout('layouts.app');

state(['selected_movie' => null, 'overview' => null, 'rating' => 2, 'movieDate' => null, 'movieTime' => null, 'dConfig' => [
    "enableTime" => true,
    "noCalendar" => true,
    "dateFormat" => "H:i",
    "time_24hr" => true
]]);

mount(function () {
    $tmdb = new \App\TMDB\WrapperApi();

    $this->selected_movie = SearchableMovie::findorFail(request('id'));

    $this->overview = $tmdb->getMovieDetails($this->selected_movie->tmdb_id)['overview'];

    $this->rating = $this->selected_movie->imdb_rating;
});

$backToMoviesSearch = function () {
    $this->dispatch('back-to-movies-search');
};

$addMovieForm = function () {
    $date = Carbon::parse($this->movieDate)->format('Y-m-d');
    $datetime = Carbon::parse($date . ' ' . $this->movieTime)->toDateTimeString();

    auth()->user()->scheduledMovies()->create([
        'movie_program_id' => auth()->user()->getDefaultMovieProgramId(),
        'movie_id' => $this->selected_movie->id,
        'scheduled_time' => $datetime
    ]);

    $this->success('Movie added to calendar');

    $this->redirect('/program');
}

?>
<section class="py-8" >

    <div class="flex flex-col">

        <h1 class="text-2xl font-bold my-4">{{ $this->selected_movie->primary_title }}</h1>


        <div class="grid grid-cols-4 gap-4">
            {{-- Movie Poster on left --}}
            <div class="col-span-1">
                <img
                    src="{{ $this->selected_movie->poster_url }}"
                    alt="{{ $this->selected_movie->primary_title }}" class="w-full h-auto"/>
                <a href="{{ $this->selected_movie->imdb_url }}" target="_blank">
                    <x-mary-badge value="Rating: {{ $rating }}" class="badge-primary"/>
                </a>
            </div>
            {{-- Movie Details on right. Text description on top below date time inputs --}}
            <div class="col-span-3 grid">
                <div> {{ $this->overview }}</div>

                {{-- for inputs --}}
                <form wire:submit="addMovieForm" class="flex flex-col align-middle p-4 gap-4">
                    <p class="text-lg font-bold ">Add Movie To Calendar</p>
                    <div class="flex flex-row gap-4">
                        <x-mary-datepicker placeholder="DD/MM/YY" wire:model="movieDate" icon="o-calendar"/>

                        <x-mary-datepicker placeholder="00:00" wire:model="movieTime" icon="o-clock" :config="$dConfig"/>
                    </div>
                    <x-mary-button type="submit" label="Add to Calendar" class="btn-primary" spinner="save"/>
                </form>


            </div>

        </div>

    </div>

</section>
