<?php

use function Livewire\Volt\{state};

state('active', false);
state('route', null);
state('label', null);

?>

<li >
    <a wire:navigate="route" class="block py-2 px-3 text-white

      rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">{{ $label }}</a>
</li>
