<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.app');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('movies', absolute: false), navigate: true);
};

?>

<section class="py-8">
    <header>
        <h2 class="text-xl  text-neutral-content-content font-bold">
            {{ __('Log in') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-content">
            {{ __('Access your account by providing your credentials.') }}
        </p>
    </header>

    <x-mary-form wire:submit="login" class="mt-6 space-y-6">
        {{-- Full error bag --}}
        <x-mary-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

        {{-- Email Address --}}
        <x-mary-input
            label="{{ __('Email Address') }}"
            wire:model="form.email"
            id="login_email"
            name="email"
            type="email"
            placeholder="{{ __('Enter your email') }}"
            icon-right="o-envelope"
        />

        {{-- Password --}}
        <x-mary-input
            label="{{ __('Password') }}"
            wire:model="form.password"
            id="login_password"
            name="password"
            type="password"
            placeholder="{{ __('Enter your password') }}"
            icon-right="o-lock-closed"
        />

        {{-- Remember Me --}}
        <x-mary-checkbox
            label="{{ __('Remember Me') }}"
            wire:model="form.remember"
            id="login_remember"
            name="remember"
        />

        {{-- Forgot Password Link --}}
        @if (Route::has('password.request'))
            <div class="text-sm text-neutral-content">
                <a class="hover:underline" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        {{-- Form Actions --}}
        <x-slot:actions>
            <x-mary-button
                label="{{ __('Log in') }}"
                class="btn-primary"
                type="submit"
                spinner="login"
            />
        </x-slot:actions>
    </x-mary-form>
</section>
