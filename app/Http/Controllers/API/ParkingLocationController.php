<?php

namespace App\Http\Controllers\API;

// app/Http/Controllers/API/ParkingLocationController.php



use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingLocationController extends Controller
{
    public function index()
    {
        // Get all active locations, or all locations if is_active is null
        $locations = ParkingLocation::where(function($query) {
            $query->where('is_active', true)
                  ->orWhereNull('is_active');
        })->get();
        
        return response()->json([
            'data' => $locations
        ]);
    }

    public function show($id)
    {
        $location = ParkingLocation::findOrFail($id);
        return response()->json([
            'data' => $location
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'price' => 'required|numeric|min:0',
            'available' => 'required|integer|min:0',
            'total' => 'required|integer|min:1',
            'features' => 'sometimes',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $location = ParkingLocation::create($validated);
        return response()->json($location, 201);
    }

    public function update(Request $request, $id)
    {
        $location = ParkingLocation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'lat' => 'sometimes|numeric|between:-90,90',
            'lng' => 'sometimes|numeric|between:-180,180',
            'price' => 'sometimes|numeric|min:0',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'available' => 'sometimes|integer|min:0',
            'total' => 'sometimes|integer|min:1',
            'distance' => 'sometimes|numeric|min:0',
            'features' => 'sometimes',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $location->update($validated);
        return response()->json($location);
    }

    public function destroy($id)
    {
        $location = ParkingLocation::findOrFail($id);
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully']);
    }
}