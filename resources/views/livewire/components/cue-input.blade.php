<?php

use function Livewire\Volt\{state};

state();

?>

<label class="form-control w-full max-w-xs">
    <div class="label">
        <span class="label-text">What is your name?</span>
        <span class="label-text-alt">Top Right label</span>
    </div>
    <input type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
    <div class="label">
        <span class="label-text-alt">Bottom Left label</span>
        <span class="label-text-alt">Bottom Right label</span>
    </div>
</label>
