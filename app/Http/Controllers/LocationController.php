<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index (): JsonResponse
    {
        return ShortResponse::json(true, 'Locations are retrieved...', Location::all());
    }

    public function withFridge (): JsonResponse
    {
        return ShortResponse::json(true, 'Locations with fridges are retrieved...', Location::with('fridge')->get());
    }

    public function locationByCity (string $city): JsonResponse
    {
        return ShortResponse::json(true, 'Location by city retrieved...',
            Location::select('id', 'city', 'district', 'name', 'coordinates')
            ->where('city', $city)
            ->get()
            ->toArray()
        );
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255'
        ]);

        return ShortResponse::json(true, 'Role created!', Location::create($data), 201);
    }

    public function update (Request $request, Location $location): JsonResponse
    {
        $data = $request->validate([
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255'
        ]);

        $location->update($data);
        return ShortResponse::json(true, 'Location updated', $location);
    }

    public function delete (Request $request, Location $location): JsonResponse
    {
        $location->delete();
        return ShortResponse::json(true, 'Location was deleted', $location);
    }

}
