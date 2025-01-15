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

    public function show($id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found.'], 404);
        }
        return response()->json($branch, 200);
    }
}
