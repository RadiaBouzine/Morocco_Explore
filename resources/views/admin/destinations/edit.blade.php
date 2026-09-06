<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Destination</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                @if ($destination->images->count())
                    <div class="mb-4 flex gap-2 flex-wrap">
                        @foreach ($destination->images as $image)
                            <img src="{{ asset('storage/' . $image->image_url) }}" class="w-20 h-20 object-cover rounded">
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Category</label>
                        <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $destination->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name', $destination->name) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea name="description" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $destination->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">City</label>
                            <input type="text" name="city" value="{{ old('city', $destination->city) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Region</label>
                            <input type="text" name="region" value="{{ old('region', $destination->region) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $destination->latitude) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $destination->longitude) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="published" {{ old('status', $destination->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="unpublished" {{ old('status', $destination->status) === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Add More Images</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                            class="mt-1 block w-full">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded">
                        Update
                    </button>
                    <a href="{{ route('admin.destinations.index') }}" class="ml-2 text-gray-600">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
