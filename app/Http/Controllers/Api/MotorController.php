<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Motor;

class MotorController extends Controller
{
    public function index()
    {
        $motors = Motor::all();
        return response()->json($motors, 200);
    }


    public function show($id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json(['message' => 'Motor not found.'], 404);
        }

        return response()->json($motor, 200);
    }
}
