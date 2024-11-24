@props([
    /** @var \App\Models\SearchableMovie */
    'result'
])
<div class="relative shadow group">
    {{-- Poster Image with darkening effect on hover --}}
    <img
        src="{{ $result->poster_path ? 'https://image.tmdb.org/t/p/w500' . $result->poster_path : 'https://via.placeholder.com/500x750?text=No+Poster' }}"
        alt="{{ $result->primary_title }}"
        class="w-full h-auto transition-transform duration-300 transform group-hover:scale-105 group-hover:opacity-50 group-hover:brightness-50"
    >

    {{-- Overlay content --}}
    <div class="absolute inset-0 flex flex-col opacity-0 group-hover:opacity-100 transition-opacity duration-300">
       <div class="flex flex-col space-x-3 items-center">
           <h2 class="text-lg line-clamp-2 font-medium text-white">{{ $result->primary_title }}</h2>
           <span class="text-sm text-gray-400">{{ $result->release_date ? $result->release_date->format('Y') : 'N/A'}}</span>
       </div>
    </div>
</div>
