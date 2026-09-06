<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('destination.category')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Destination $destination)
    {
        $exists = Favorite::where('user_id', auth()->id())
            ->where('destination_id', $destination->id)
            ->exists();

        if (! $exists) {
            Favorite::create([
                'user_id' => auth()->id(),
                'destination_id' => $destination->id,
            ]);
        }

        return back()->with('success', 'Added to favorites.');
    }

    public function destroy(Destination $destination)
    {
        Favorite::where('user_id', auth()->id())
            ->where('destination_id', $destination->id)
            ->delete();

        return back()->with('success', 'Removed from favorites.');
    }
}
