<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Destinations</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('admin.destinations.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded">
                + Add Destination
            </a>

            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Category</th>
                            <th class="py-2">City</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($destinations as $destination)
                            <tr class="border-b">
                                <td class="py-2">{{ $destination->name }}</td>
                                <td class="py-2">{{ $destination->category->name }}</td>
                                <td class="py-2">{{ $destination->city }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $destination->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $destination->status }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center">No destinations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
