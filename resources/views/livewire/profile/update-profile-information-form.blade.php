<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use function Livewire\Volt\state;

state([
    'name' => fn() => auth()->user()->name,
    'email' => fn() => auth()->user()->email
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
    ]);

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    $this->dispatch('profile-updated', name: $user->name);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section>
    <header>
        <h2 class="text-xl">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm ">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <x-mary-form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        {{-- Full error bag --}}
        <x-mary-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

        <div>
            <x-mary-input wire:model="name" id="name" name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter your name') }}" icon="o-user" autofocus autocomplete="name" required />
        </div>

        <div>
            <x-mary-input wire:model="email" id="email" name="email" label="{{ __('Email') }}" placeholder="{{ __('Enter your email') }}" icon="o-envelope" autocomplete="username" required />

            @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <x-mary-button wire:click="sendVerification"
                                       class="btn-link"
                                       spinner="sendVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </x-mary-button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="updateProfileInformation" />

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </x-slot:actions>
    </x-mary-form>
</section>
