<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Reviews</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">User</th>
                            <th class="py-2">Destination</th>
                            <th class="py-2">Content</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr class="border-b">
                                <td class="py-2">{{ $review->user->name }}</td>
                                <td class="py-2">{{ $review->destination->name }}</td>
                                <td class="py-2">{{ Str::limit($review->content, 60) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if($review->status === 'approved') bg-green-100 text-green-700
                                        @elseif($review->status === 'hidden') bg-gray-200 text-gray-600
                                        @else bg-yellow-100 text-yellow-700
                                        @endif">
                                        {{ $review->status }}
                                    </span>
                                </td>
                                <td class="py-2 space-x-2">
                                    @if ($review->status !== 'approved')
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-green-600">Approve</button>
                                        </form>
                                    @endif
                                    @if ($review->status !== 'hidden')
                                        <form action="{{ route('admin.reviews.hide', $review) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-600">Hide</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center">No reviews yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
