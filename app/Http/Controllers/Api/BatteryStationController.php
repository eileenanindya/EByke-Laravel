<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Battery_stations;
use App\Http\Controllers\Controller;

class BatteryStationController extends Controller
{
    public function index(){
        $stations = Battery_stations::all();
        return response()->json($stations, 200);
    }

    public function show($id){
        $station = Battery_stations::find($id);
        if(!$station){
            return response()->json(['message' => 'Battery station not found.'], 404);
        }
        return response()->json($station, 200);
    }
}
