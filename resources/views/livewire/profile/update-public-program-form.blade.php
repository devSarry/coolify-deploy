<?php

use function Livewire\Volt\{state, mount};

state(['makePublic' => null, 'movieProgramHash' => null]);

mount(function () {
    $this->makePublic = (boolean)auth()->user()->getDefaultMovieProgram()->is_public;
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

        <div class="w-full max-w-sm" x-data="{
    url: '{{ route('public-movie-program', auth()->user()->getDefaultMovieProgram()->hash_id) }}',
    copied: false,
    copyToClipboard() {
        navigator.clipboard.writeText(this.url).then(() => {
            this.copied = true;
            setTimeout(() => { this.copied = false }, 2000);
        });
    }
}">

            <div class="flex">
                <x-mary-input id="website-url" type="text" ::value="url">
                    <x-slot:prepend>
                        <span class="mx-2">URL</span>
                    </x-slot:prepend>
                    <x-slot:append>
                        <x-mary-button ::class="!copied ? 'btn-base' : 'btn-success'" @click="copyToClipboard"
                                       ::color="copied ? 'success' : 'info'" class="rounded-s-none"
                                       ::tooltip="copied ? 'Copied!' : 'Copy link'">
                        <span :class="{ 'hidden': copied }">
                            <x-mary-icon name="o-clipboard" class="w-4 h-4"/>
                        </span>
                        <span x-show="copied" class="inline-flex items-center">
                        <x-mary-icon name="o-check" class="w-4 h-4"/>
                        </span>
                        </x-mary-button>
                    </x-slot:append>
                </x-mary-input>
        </div>
    @endif
</section>
