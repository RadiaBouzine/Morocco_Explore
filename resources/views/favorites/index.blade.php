<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Favorites</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($favorites as $favorite)
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <h3 class="text-lg font-bold">{{ $favorite->destination->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $favorite->destination->category->name }}</p>
                        <a href="{{ route('destinations.show', $favorite->destination) }}" class="text-blue-600 text-sm">View details</a>

                        <form action="{{ route('favorites.destroy', $favorite->destination) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-400">You haven't saved any favorites yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
