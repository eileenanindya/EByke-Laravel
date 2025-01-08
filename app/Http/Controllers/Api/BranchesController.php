<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branches;

class BranchesController extends Controller
{
    public function index()
    {
        $branches = Branches::all();
        return response()->json($branches, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name' => 'required|string|max:255',
            'branch_address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        $branch = Branches::create($validated);
        return response()->json($branch, 201);
    }

    public function show($id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found.'], 404);
        }
        return response()->json($branch, 200);
    }

    public function update(Request $request, $id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found.'], 404);
        }

        $validated = $request->validate([
            'branch_name' => 'nullable|string|max:255',
            'branch_address' => 'nullable|string',
            'longitude' => 'nullable|numeric', // Longitude harus disertakan
            'latitude' => 'nullable|numeric',
        ]);

        $branch->update($validated);
        return response()->json($branch, 200);
    }

    public function destroy($id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found.'], 404);
        }

        $branch->delete();
        return response()->json(['message' => 'Branch deleted successfully.'], 200);
    }
}
