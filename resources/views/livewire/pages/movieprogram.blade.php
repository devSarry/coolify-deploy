<?php

use function Livewire\Volt\{state, layout};

state(['count' => 0]);

layout('layouts.app');

$increment = function () {
    $this->count++;
};
?>



    <div class="flex flex-col mx-auto">
        <x-mary-button wire:click="increment">incr</x-mary-button>
        The number goes up {{ $count }}

    </div>


