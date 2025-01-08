<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Battery_stations;

class BatteryStationController extends Controller
{
    public function index(){
        $stations = Battery_stations::all();
        return response->json($stations, 200);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'station_name' => 'required|string|max:255',
            'station_address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'total_Slots' => 'required|integer|min:1',
            'available_slots' => 'required|integer|min:0',
        ]);

        $station = Battery_stations::create($validated);
        return response()->json($station, 201);
    }

    public function show($id){
        $station = Battery_stations::find($id);
        if(!$station){
            return response()->json(['message' => 'Battery station not found.'], 404);
        }
        return response()->json($station, 200);
    }

    public function update(Request $request, $id){
        $station = Battery_stations::find($id);
        if(!$station){
            return response()->json(['message' => 'Battery station not found.'], 404);
        }

        $validated = $request->validate([
            'station_name' => 'nullable|string|max:255',
            'station_address' => 'nullable|string',
            'longitude' => 'nullable|numeric', // Longitude harus disertakan
            'latitude' => 'nullable|numeric',
            'total_Slots' => 'nullable|integer|min:1',
            'available_slots' => 'nullable|integer|min:0',
        ]);

        $station->update($validated);
        return response()->json($station, 200);
    }

    public function destroy($id)
    {
        $station = Battery_stations::find($id);
        if (!$station) {
            return response()->json(['message' => 'Battery station not found.'], 404);
        }

        $station->delete();
        return response()->json(['message' => 'Battery station deleted successfully.'], 200);
    }
}
