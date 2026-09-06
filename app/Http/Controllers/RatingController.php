<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'value' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'destination_id' => $destination->id,
            ],
            [
                'value' => $validated['value'],
            ]
        );

        return back()->with('success', 'Thanks for rating!');
    }
}
