<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>



<nav class="bg-primary dark:bg-primary fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <!-- Logo -->
        <a href="{{ auth()->guest() ? '/' : '/program' }}" wire:navigate>
            <img class="h-[32px] fill-primary-content" src="{{ Vite::asset('resources/images/cue-logo.svg') }}" alt="cue logo">
        </a>

        <!-- Right side navigation (desktop and mobile behavior) -->
        <div class="flex space-x-3 md:space-x-0 rtl:space-x-reverse md:order-2">
            <!-- Sign-Up Button for Guests -->
            @if(auth()->guest())
                <x-mary-button href="/register" wire:navigate class="md:block">Sign Up</x-mary-button>
            @endif

            @auth
                <!-- Hamburger Icon for Small Screens -->
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>

                <!-- Dropdown Menu for Desktop -->
                <x-mary-dropdown class="hidden md:block">
                    <x-slot:trigger>
                        <x-mary-button icon="m-bars-3" class="btn-accent hidden md:block" />
                    </x-slot:trigger>
                    <x-mary-menu-item title="Profile" icon="o-user" :href="route('profile')" />
                    <x-mary-menu-item title="Logout" icon="tabler.logout" wire:click="logout" />
                </x-mary-dropdown>
            @endauth
        </div>

        <!-- Collapsible Navigation for Small Screens -->
        @auth
            <div class="items-center justify-between hidden w-full md:hidden" id="navbar-sticky">
                <ul class="flex flex-col p-4 mt-4 font-medium border border-gray-100 rounded-lg bg-brand-100 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <x-nav-list-item route="/profile">
                        Profile
                    </x-nav-list-item>
                    <li>
                        <a href="#" wire:click="logout" class="block py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>



