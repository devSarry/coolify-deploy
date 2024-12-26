<?php

use function Livewire\Volt\{state, mount};

state(['makePublic' => null, 'movieProgramHash' => null]);

mount(function () {
    $this->makePublic = (boolean) auth()->user()->is_program_public;
});

$updatePublicProgramVisibility = function () {
    auth()->user()->getDefaultMovieProgram()->update([
        'is_public' => $this->makePublic
    ]);
};


?>

<section class="space-y-6">
    <header>
        <h2 class="text-xl">
            {{ __('Make Public') }}
        </h2>

        <p class="mt-1 text-sm ">
            {{ __("Will make your movie program public and sharable via a unique url.") }}
        </p>
    </header>

    <x-mary-toggle
        label="Reveal Shareable URL"

        wire:model="makePublic"
        wire:click="updatePublicProgramVisibility"
    />

    @if ($makePublic)
        <div class="mt-4">
            {{ route('public-movie-program',   auth()->user()->getDefaultMovieProgram()->hash_id) }}
        </div>
    @endif
</section>
