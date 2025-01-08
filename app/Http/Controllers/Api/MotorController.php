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

    // Menambahkan motor baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'registration_number' => 'required|string|max:255|unique:motors,registration_number',
            'battery_capacity' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|in:available,in-use,maintenance',
        ]);

        $motor = Motor::create($validated);

        return response()->json([
            'message' => 'Motor created successfully.',
            'motor' => $motor,
        ], 201);
    }

    // Menampilkan motor berdasarkan ID
    public function show($id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json(['message' => 'Motor not found.'], 404);
        }

        return response()->json($motor, 200);
    }

    // Memperbarui motor berdasarkan ID
    public function update(Request $request, $id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json(['message' => 'Motor not found.'], 404);
        }

        $validated = $request->validate([
            'model' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'registration_number' => "nullable|string|max:255|unique:motors,registration_number,{$id}",
            'battery_capacity' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|in:available,in-use,maintenance',
        ]);

        $motor->update($validated);

        return response()->json([
            'message' => 'Motor updated successfully.',
            'motor' => $motor,
        ], 200);
    }

    // Menghapus motor berdasarkan ID
    public function destroy($id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json(['message' => 'Motor not found.'], 404);
        }

        $motor->delete();

        return response()->json(['message' => 'Motor deleted successfully.'], 200);
    }
}
