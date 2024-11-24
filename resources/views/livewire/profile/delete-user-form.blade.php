<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state(['password' => '', 'confirmUserDeletion' => false]);

rules(['password' => ['required', 'string', 'current_password']]);

$deleteUser = function (Logout $logout) {
    $this->validate();

    tap(Auth::user(), $logout(...))->delete();

    $this->redirect('/', navigate: true);
};

?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-mary-button
        label="{{ __('Delete Account') }}"
        class="btn-error btn-outline"
        x-data=""
        @click="$wire.confirmUserDeletion = true"
    />

    <x-mary-modal  wire:model="confirmUserDeletion" class="backdrop-blur">
        <form wire:submit="deleteUser" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-mary-input
                    label="{{ __('Password') }}"
                    wire:model="password"
                    type="password"
                    placeholder="{{ __('Password') }}"
                    icon-right="o-key"
                />
            </div>

            <div class="mt-6 flex justify-end">
                <x-mary-button
                    label="{{ __('Cancel') }}"
                    class="btn-ghost"
                    @click="$wire.confirmUserDeletion = false"
                />

                <x-mary-button
                    label="{{ __('Delete Account') }}"
                    class="btn-error btn-outline ms-3"
                    wire:click="deleteUser"
                />
            </div>
        </form>
    </x-mary-modal>
</section>
