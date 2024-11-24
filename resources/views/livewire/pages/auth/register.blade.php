<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.app');

state([
    'name' => '',
    'email' => '',
    'password' => '',
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', Rules\Password::defaults()],
]);


$register = function () {
    DB::beginTransaction();

    try {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->movieProgram()->create([
            'hash_id' => Str::uuid(),
        ]);

        DB::commit();

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    } catch (\Exception $e) {
        DB::rollBack();

        throw $e;
    }
};

?>

<section class="py-8">
    <header >
        <h2 class="text-lg font-medium text-neutral-content">
            {{ __('Register') }}
        </h2>

        <p class="mt-1 text-sm  text-gray-400">
            {{ __('Create a new account to access the platform.') }}
        </p>
    </header>

    <x-mary-form wire:submit="register" class="mt-6 space-y-6">
        {{-- Full error bag --}}
        <x-mary-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

        {{-- Name --}}
        <x-mary-input
            label="{{ __('Name') }}"
            wire:model="name"
            id="register_name"
            name="name"
            type="text"
            placeholder="{{ __('Enter your name') }}"
            icon-right="o-user"
        />

        {{-- Email --}}
        <x-mary-input
            label="{{ __('Email') }}"
            wire:model="email"
            id="register_email"
            name="email"
            type="email"
            placeholder="{{ __('Enter your email') }}"
            icon-right="o-envelope"
        />

        {{-- Password --}}
        <x-mary-input
            label="{{ __('Password') }}"
            wire:model="password"
            id="register_password"
            name="password"
            type="password"
            placeholder="{{ __('Enter your password') }}"
            icon-right="o-key"
        />

        <hr class="my-3" />

        {{-- Form Actions --}}
        <div class="flex justify-between items-center">
            <x-mary-button
                label="{{ __('Register') }}"
                class="btn-primary"
                type="submit"
                spinner="register"
            />
            <a class="underline text-sm text-neutral-content hover:text-accent dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}" >
                {{ __('Already registered?') }}
            </a>

        </div>
    </x-mary-form>
</section>
