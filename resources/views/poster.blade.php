@php
    use App\Models\SearchableMovie;

    $data = SearchableMovie::where('tmdb_id', 278)->first();


@endphp


<x-app-layout>

    <div class="py-12">
        <div class="max-w-2xl mx-auto">
            <div class="p-4 sm:p-8 bg-base-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div wire:transition>
                    <livewire:pages.movies.create-movie-program :selected_movie="$data"/>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
