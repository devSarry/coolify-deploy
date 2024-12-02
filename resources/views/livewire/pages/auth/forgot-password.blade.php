<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.app');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<section class="py-8">
    <header>
        <h2 class="text-lg font-bold text-gray-100">
            {{ __('Forgot Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just provide your email address, and we will email you a password reset link.') }}
        </p>
    </header>

    <x-mary-form wire:submit="sendPasswordResetLink" class="mt-6 space-y-6">
        {{-- Full error bag --}}
        <x-mary-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

        {{-- Email Address --}}
        <x-mary-input
            label="{{ __('Email Address') }}"
            wire:model="email"
            id="forgot_password_email"
            name="email"
            type="email"
            placeholder="{{ __('Enter your email') }}"
            icon-right="o-envelope"
        />

        {{-- Session Status --}}
        @if (session('status'))
            <x-mary-alert
                type="success"
                title="Success"
                message="{{ session('status') }}"
                icon="o-check-circle"
            />
        @endif

        {{-- Form Actions --}}
        <x-slot:actions>
            <x-mary-button
                label="{{ __('Email Password Reset Link') }}"
                class="btn-primary"
                type="submit"
                spinner="sendPasswordResetLink"
            />
        </x-slot:actions>
    </x-mary-form>
</section>

