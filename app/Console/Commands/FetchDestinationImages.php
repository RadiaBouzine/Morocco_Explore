<?php

namespace App\Console\Commands;

use App\Models\Destination;
use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchDestinationImages extends Command
{
    protected $signature = 'destinations:fetch-images';
    protected $description = 'Fetch one image per destination from Pexels and store it locally';

    public function handle()
    {
        $apiKey = env('PEXELS_API_KEY');

        if (! $apiKey) {
            $this->error('PEXELS_API_KEY not found in .env');
            return 1;
        }

        $destinations = Destination::doesntHave('images')->get();
        $this->info("Found {$destinations->count()} destinations without images.");

        foreach ($destinations as $destination) {
            $query = urlencode($destination->name . ' Morocco');

            $response = Http::withHeaders(['Authorization' => $apiKey])
                ->get("https://api.pexels.com/v1/search", [
                    'query' => $query,
                    'per_page' => 1,
                ]);

            if (! $response->successful()) {
                $this->warn("Failed request for: {$destination->name}");
                continue;
            }

            $data = $response->json();

            if (empty($data['photos'])) {
                $this->warn("No image found for: {$destination->name}");
                continue;
            }

            $imageUrl = $data['photos'][0]['src']['large'];
            $imageContents = Http::get($imageUrl)->body();

            $filename = 'destinations/' . uniqid() . '.jpg';
            \Storage::disk('public')->put($filename, $imageContents);

            Image::create([
                'destination_id' => $destination->id,
                'image_url' => $filename,
                'is_primary' => true,
            ]);

            $this->info("Image saved for: {$destination->name}");

            usleep(500000); // half a second pause to respect API rate limits
        }

        $this->info('Done!');
        return 0;
    }
}
