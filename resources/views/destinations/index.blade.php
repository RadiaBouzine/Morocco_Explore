<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Destinations') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <form method="GET" action="{{ route('destinations.index') }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, city, region..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Filter
            </button>
            <a href="{{ route('destinations.index') }}" class="px-4 py-2 text-gray-600">Clear</a>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($destinations as $destination)
    <a href="{{ route('destinations.show', $destination) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
        <div class="overflow-hidden">
    @if ($destination->images->count())
        <img src="{{ asset('storage/' . $destination->images->first()->image_url) }}" class="w-full h-56 object-cover hover:scale-110 transition duration-300">
    @else
        <div class="w-full h-56 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">No image</div>
    @endif
</div>
        <div class="p-4">
            <h3 class="text-lg font-bold">{{ $destination->name }}</h3>
            <p class="text-sm text-gray-500">{{ $destination->city }}, {{ $destination->region }}</p>
            <p class="mt-2 text-gray-700">{{ Str::limit($destination->description, 100) }}</p>
            <p class="mt-2 text-xs text-gray-400">Category: {{ $destination->category->name }}</p>
        </div>
    </a>
@empty
    <p>No destinations found.</p>
@endforelse
            </div>
        </div>
    </div>
</x-app-layout>
