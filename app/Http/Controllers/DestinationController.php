<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Image;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    // Public listing (visitors)
    public function index(Request $request)
{
    $query = Destination::where('status', 'published')->with('category', 'images');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('region', 'like', "%{$search}%");
        });
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $destinations = $query->get();
    $categories = Category::all();

    return view('destinations.index', compact('destinations', 'categories'));
}

    // Admin: list all destinations
    public function adminIndex()
    {
        $destinations = Destination::with('category')->get();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:published,unpublished',
            'images.*' => 'nullable|image|max:8192',
        ]);

        $destination = Destination::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('destinations', 'public');
                Image::create([
                    'destination_id' => $destination->id,
                    'image_url' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::all();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:published,unpublished',
            'images.*' => 'nullable|image|max:8192',
        ]);

        $destination->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('destinations', 'public');
                Image::create([
                    'destination_id' => $destination->id,
                    'image_url' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted successfully.');
    }

    // Public: destination details
    public function show(Destination $destination)
    {
        $destination->load('category', 'images', 'reviews.user');
        return view('destinations.show', compact('destination'));
    }
}
