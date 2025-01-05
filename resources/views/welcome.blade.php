<x-app-layout>
    <section class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-primary mb-4">Never Miss a Movie Night Again</h1>
            <p class="text-lg text-neutral-content mb-8">Plan your movie schedules, share with friends, and enjoy!</p>
            <x-mary-button link="{{ route('register') }}" label="Get Started" class="btn-primary mr-4"/>
            @guest
                <x-mary-button link="{{ route('login') }}" label="Login" class="btn-base"/>
            @endguest

        </div>
    </section>

    <section class="container mx-auto px-4 py-16">
        <div class="mockup-browser bg-base-300 border">
            <div class="mockup-browser-toolbar">
            </div>
            <picture>
                <source media="(min-width:650px)" srcset="{{ Vite::asset('resources/images/screen-shot-program.png') }}">
                <source media="(min-width:465px)" srcset="{{ Vite::asset('resources/images/screen-shot-program.png') }}">
                <img src="{{ Vite::asset('resources/images/screen-shot-program.png') }}" alt="Flowers" style="width:auto;">
            </picture>
        </div>
    </section>

</x-app-layout>
