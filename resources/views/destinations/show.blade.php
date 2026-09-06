<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $destination->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Images --}}
                @if ($destination->images->count())
    <div class="grid grid-cols-3 gap-2 mb-6">
        @foreach ($destination->images as $image)
            <div class="overflow-hidden rounded">
                <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-64 object-cover hover:scale-110 transition duration-300">
            </div>
        @endforeach
    </div>
@else
                    <div class="mb-6 h-40 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                        No images available
                    </div>
                @endif

                {{-- Basic info --}}
                <div class="mb-4">
                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                        {{ $destination->category->name }}
                    </span>
                    <span class="text-sm text-gray-500 ml-2">
                        {{ $destination->city }}, {{ $destination->region }}
                    </span>
                </div>

                <p class="text-gray-700 mb-6">{{ $destination->description }}</p>
                @auth
    @php
        $isFavorited = auth()->user()->favorites()->where('destination_id', $destination->id)->exists();
    @endphp

    @if ($isFavorited)
        <form action="{{ route('favorites.destroy', $destination) }}" method="POST" class="mb-6">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200">
                ★ Remove from Favorites
            </button>
        </form>
    @else
        <form action="{{ route('favorites.store', $destination) }}" method="POST" class="mb-6">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                ☆ Add to Favorites
            </button>
        </form>
    @endif
@endauth
                {{-- Average rating --}}
                <div class="mb-6">
                    <span class="font-medium text-gray-700">Average Rating:</span>
                    @if ($destination->ratings->count())
                        <span class="text-yellow-500 font-bold">{{ number_format($destination->averageRating(), 1) }} / 5</span>
                        <span class="text-gray-400 text-sm">({{ $destination->ratings->count() }} ratings)</span>
                    @else
                        <span class="text-gray-400">No ratings yet</span>
                    @endif
                </div>

                {{-- Map (if coordinates exist) --}}
                @if ($destination->latitude && $destination->longitude)
                    <div class="mb-6">
                        <iframe
                            width="100%" height="300" style="border:0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $destination->longitude - 0.01 }}%2C{{ $destination->latitude - 0.01 }}%2C{{ $destination->longitude + 0.01 }}%2C{{ $destination->latitude + 0.01 }}&marker={{ $destination->latitude }}%2C{{ $destination->longitude }}">
                        </iframe>
                    </div>
                @endif

                {{-- Rating & Review forms (registered users only) --}}
@auth
    <div class="border-t pt-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Rate this destination</h3>
        <form action="{{ route('ratings.store', $destination) }}" method="POST" class="flex gap-2 mb-2">
            @csrf
            @for ($i = 1; $i <= 5; $i++)
                <button type="submit" name="value" value="{{ $i }}" class="text-2xl text-yellow-400 hover:scale-110">★</button>
            @endfor
        </form>
        @error('value')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <h3 class="font-semibold text-lg mb-2 mt-6">Write a review</h3>
        <form action="{{ route('reviews.store', $destination) }}" method="POST">
            @csrf
            <textarea name="content" rows="3" placeholder="Share your experience..."
                class="w-full border-gray-300 rounded-md shadow-sm">{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Submit Review
            </button>
        </form>
    </div>
@endauth

{{-- Reviews --}}
<div class="border-t pt-6">
    <h3 class="font-semibold text-lg mb-4">Reviews</h3>

    @forelse ($destination->reviews->where('status', 'approved') as $review)
        <div class="mb-4 pb-4 border-b">
            <p class="font-medium">{{ $review->user->name }}</p>
            <p class="text-gray-600">{{ $review->content }}</p>
        </div>
    @empty
        <p class="text-gray-400">No reviews yet.</p>
    @endforelse
</div>

            </div>

            <a href="{{ route('destinations.index') }}" class="inline-block mt-4 text-blue-600">← Back to Destinations</a>
        </div>
    </div>
</x-app-layout>
