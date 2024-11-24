<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state([
    'current_password' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'current_password' => ['required', 'string', 'current_password'],
    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
]);

$updatePassword = function () {
    try {
        $validated = $this->validate();
    } catch (ValidationException $e) {
        $this->reset('current_password', 'password', 'password_confirmation');

        throw $e;
    }

    Auth::user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    $this->reset('current_password', 'password', 'password_confirmation');

    $this->dispatch('password-updated');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <x-mary-form wire:submit="updatePassword" class="mt-6 space-y-6">
        {{-- Full error bag --}}
        <x-mary-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

        {{-- Current Password --}}
        <x-mary-input
            label="{{ __('Current Password') }}"
            wire:model="current_password"
            id="update_password_current_password"
            name="current_password"
            type="password"
            placeholder="{{ __('Enter your current password') }}"
            icon-right="o-key"
        />

        {{-- New Password --}}
        <x-mary-input
            label="{{ __('New Password') }}"
            wire:model="password"
            id="update_password_password"
            name="password"
            type="password"
            placeholder="{{ __('Enter your new password') }}"
            icon-right="o-key"
        />

        {{-- Confirm Password --}}
        <x-mary-input
            label="{{ __('Confirm Password') }}"
            wire:model="password_confirmation"
            id="update_password_password_confirmation"
            name="password_confirmation"
            type="password"
            placeholder="{{ __('Confirm your new password') }}"
            icon-right="o-key"
        />

        {{-- Form Actions --}}
        <x-slot:actions>
            <x-mary-button
                label="{{ __('Save') }}"
                class="btn-primary"
                type="submit"
                spinner="updatePassword"
            />
            <x-action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </x-slot:actions>
    </x-mary-form>
</section>
