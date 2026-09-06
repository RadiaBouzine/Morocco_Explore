<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Categories</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('admin.categories.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded">
    + Add Category
</a>

            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Description</th>
                            <th class="py-2"># Destinations</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="border-b">
                                <td class="py-2">{{ $category->name }}</td>
                                <td class="py-2">{{ Str::limit($category->description, 50) }}</td>
                                <td class="py-2">{{ $category->destinations_count }}</td>
                                <td class="py-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
