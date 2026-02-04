<?php

namespace App\Http\Controllers\API;

// app/Http/Controllers/API/ParkingLocationController.php



use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'price' => 'required|numeric',
            'available' => 'required|integer',
            'total' => 'required|integer',
            'features' => 'required|array',
        ]);

        $location = ParkingLocation::create($request->all());
        return response()->json($location, 201);
    }

    public function update(Request $request, $id)
    {
        $location = ParkingLocation::findOrFail($id);
        $location->update($request->all());
        return response()->json($location);
    }

    public function destroy($id)
    {
        $location = ParkingLocation::findOrFail($id);
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully']);
    }
}