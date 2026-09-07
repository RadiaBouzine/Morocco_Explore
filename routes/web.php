<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Models\Destination;
use App\Models\Category;

Route::get('/', function () {
    $featuredDestinations = Destination::where('status', 'published')
        ->with('category', 'images')
        ->inRandomOrder()
        ->take(6)
        ->get();

    $categories = Category::withCount('destinations')->get();
    $totalDestinations = Destination::where('status', 'published')->count();

    return view('welcome', compact('featuredDestinations', 'categories', 'totalDestinations'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/favorites/{destination}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{destination}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/destinations/{destination}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::post('/destinations/{destination}/ratings', [RatingController::class, 'store'])->name('ratings.store');
});
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('destinations', DestinationController::class)->except(['index']);
    Route::get('destinations', [DestinationController::class, 'adminIndex'])->name('destinations.index');
    Route::get('reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
    Route::delete('reviews/{review}', [ReviewController::class, 'adminDestroy'])->name('reviews.destroy');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/sitemap.xml', function () {
    $destinations = \App\Models\Destination::where('status', 'published')->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $xml .= '<url><loc>' . url('/') . '</loc><priority>1.0</priority></url>';
    $xml .= '<url><loc>' . route('destinations.index') . '</loc><priority>0.9</priority></url>';

    foreach ($destinations as $destination) {
        $xml .= '<url><loc>' . route('destinations.show', $destination) . '</loc><priority>0.8</priority></url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'text/xml');
});

require __DIR__.'/auth.php';
