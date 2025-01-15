<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Motor;
use App\Models\Branches;
use App\Models\Transactions;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    // Menampilkan semua rental
    public function index()
    {
        $rentals = Rental::with(['user', 'motor', 'pickupBranch', 'returnBranch'])->get();
        return response()->json($rentals, 200);
    }

    public function store(Request $request)
    {
        // Validasi input yang diterima
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pickup_branch_id' => 'required|exists:branches,branch_id',
            'return_branch_id' => 'nullable|exists:branches,branch_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'motor_id' => 'required|exists:motors,id', // Validasi motor yang ada
        ]);

        // $validated['motor_id'] = (int) $validated['motor_id'];
        // Ambil data user yang sedang login
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Ambil data motor
        $motor = Motor::find($validated['motor_id']);
        if (!$motor || $motor->status !== 'available') {
            return response()->json(['message' => 'Motor is not available for rental.'], 400);
        }

        $startDateTime = Carbon::parse("{$validated['start_date']} {$validated['start_time']}");
        $endDateTime = Carbon::parse("{$validated['end_date']} {$validated['end_time']}");

        $duration = $startDateTime->diffInHours($endDateTime);

        $pricePerHour = 10000;

        $totalCost = $duration * $pricePerHour;

        // Membuat rental baru dengan data yang sudah divalidasi
        $rental = Rental::create([
            'user_id' => $user->id, // Menambahkan ID user yang login
            'motor_id' => $validated['motor_id'],
            'pickup_branch_id' => $validated['pickup_branch_id'],
            'return_branch_id' => $validated['return_branch_id'] ?? null, // Jika return_branch_id null, gunakan nilai default
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'active', // Status rental yang baru dibuat
            'total_cost' => $totalCost, // Biaya dihitung saat rental selesai
            'duration' => $duration, // Durasi dihitung saat rental selesai
        ]);

        Motor::where('id', $request->motor_id)->update(['status' => 'in-use']);

        return response()->json([
            'message' => 'Rental created successfully.',
            'rental' => $rental, // Rental yang baru dibuat
        ], 201);
    }

    public function rentalSummary($rentalId) {
        $rental = Rental::find($rentalId);
        $transaction = Transaction::where('rental_id', $rentalId)->first();
    
        if (!$rental || !$transaction) {
            return response()->json(['message' => 'Rental or transaction not found'], 404);
        }
    
        return response()->json([
            'rental' => $rental,
            'transaction' => $transaction,
        ]);
    }

    public function finishRental($rentalId)
    {
        // Cari rental berdasarkan ID
        $rental = Rental::find($rentalId);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        // Mengubah status rental menjadi 'completed'
        $rental->status = 'completed';
        $rental->save();

        return response()->json([
            'message' => 'Rental marked as completed successfully.',
            'rental' => $rental
        ], 200);
    }


    // Menampilkan rental berdasarkan ID
    public function show($id)
    {
        $rental = Rental::with(['user', 'motor', 'pickupBranch', 'returnBranch'])->find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found.'], 404);
        }

        return response()->json([
            'rental_id' => $rental->id,
            'user_id' => $rental->user_id,
            'motor_id' => $rental->motor_id,
            'start_date' => $rental->start_time,
            'end_date' => $rental->end_time,
            'total_cost' => $rental->total_cost,
            'pickup_location' => [
                'branch_id' => $rental->pickupBranch->id,
                'branch_name' => $rental->pickupBranch->name,
                'latitude' => $rental->pickupBranch->latitude,
                'longitude' => $rental->pickupBranch->longitude,
            ],
            'return_location' => [
                'branch_id' => $rental->returnBranch->id,
                'branch_name' => $rental->returnBranch->name,
                'latitude' => $rental->returnBranch->latitude,
                'longitude' => $rental->returnBranch->longitude,
            ],
        ]);
    }
}