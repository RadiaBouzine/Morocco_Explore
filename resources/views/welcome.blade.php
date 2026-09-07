<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-site-verification" content="icXTbfDsR1mLL2MYFvGpxDIsR8KRq3yBQYvFVzwJO88" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Morocco Explore — Discover Tourist Destinations in Morocco</title>
    <meta name="description" content="Explore 100+ tourist destinations across Morocco — from Chefchaouen and Marrakech to the Sahara desert. Browse, search, save favorites, and read reviews.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-blue-700">🇲🇦 Morocco Explore</span>
            <div class="space-x-4">
                <a href="{{ route('destinations.index') }}" class="text-gray-600 hover:text-blue-700">Destinations</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <div class="bg-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Discover the Beauty of Morocco</h1>
            <p class="text-lg text-blue-100 mb-8">From the blue streets of Chefchaouen to the golden dunes of Merzouga — explore {{ $totalDestinations }} destinations across the Kingdom.</p>
            <a href="{{ route('destinations.index') }}" class="inline-block px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100">
                Start Exploring
            </a>
        </div>
    </div>

    {{-- Categories --}}
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6 text-center">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('destinations.index', ['category_id' => $category->id]) }}"
                   class="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition">
                    <p class="font-semibold text-lg">{{ $category->name }}</p>
                    <p class="text-sm text-gray-500">{{ $category->destinations_count }} places</p>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Featured Destinations --}}
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6 text-center">Featured Destinations</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($featuredDestinations as $destination)
                <a href="{{ route('destinations.show', $destination) }}" class="block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    @if ($destination->images->count())
                        <img src="{{ asset('storage/' . $destination->images->first()->image_url) }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $destination->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $destination->city }}, {{ $destination->region }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('destinations.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                View All {{ $totalDestinations }} Destinations
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-300 text-center py-6 mt-12">
        <p>&copy; {{ date('Y') }} Morocco Explore — A personal tourism platform project.</p>
    </footer>

</body>
</html>

