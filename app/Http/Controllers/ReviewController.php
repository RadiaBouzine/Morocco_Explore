<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'destination_id' => $destination->id,
            'content' => $validated['content'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your review was submitted and is awaiting approval.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }

        public function adminIndex()
    {
        $reviews = Review::with('user', 'destination')->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved.');
    }

    public function hide(Review $review)
    {
        $review->update(['status' => 'hidden']);
        return back()->with('success', 'Review hidden.');
    }

    public function adminDestroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
