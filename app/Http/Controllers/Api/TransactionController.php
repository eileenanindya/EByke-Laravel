<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Rental;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi dengan relasi ke rentals
        $transactions = Transactions::with('rental')->get();

        return response()->json($transactions, 200);
    }

    public function createTransaction(Request $request)
    {
        $rental = Rental::find($request->rental_id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found.'], 404);
        }

        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            // 'amount' => 'required|numeric',
        ]);

        $transaction = Transactions::create([
            'rental_id' => $rental->id,
            'user_id' => $rental->user_id,
            'payment_method' => $request->payment_method,
            'amount' => $rental->total_cost,
            'payment_status' => 'pending',
            'payment_time' => null,
            // 'start_date' => $rental->start_date,
            // 'end_date' => $rental->end_date,
            // 'start_time' => $rental->start_time,
            // 'end_time' => $rental->end_time,
            // 'pickup_branch_id' => $rental->pickup_branch_id,
        ]);

        return response()->json($transaction, 201);
    }

    public function getTransactionSummary($id)
    {
        $transaction = Transactions::with('rental')->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        return response()->json($transaction, 200);
    }
    public function updatePaymentStatus(Request $request, $id)
    {
        // Validasi data request
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        // Cari transaksi berdasarkan ID
        $transaction = Transactions::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status pembayaran
        $transaction->payment_status = $request->payment_status;
        $transaction->payment_time = $request->payment_status === 'completed' ? now() : null;
        $transaction->save();

        return response()->json($transaction, 200);
    }
    //  /**
    //  * Display a listing of transactions.
    //  */
    // public function index()
    // {
    //     $transactions = Transaction::with(['rental.pickupBranch', 'rental.returnBranch'])->get();

    //     return response()->json($transactions);
    // }

    // /**
    //  * Store a newly created transaction.
    //  */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'rental_id' => 'required|exists:rentals,id',
    //         'user_id' => 'required|exists:users,id',
    //         'payment_method' => 'required|string',
    //         'amount' => 'required|numeric|min:0',
    //         'payment_status' => 'required|string|in:pending,successful,failed',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $transaction = Transaction::create($request->all());

    //     return response()->json(['message' => 'Transaction created successfully.', 'data' => $transaction], 201);
    // }

    // /**
    //  * Display the specified transaction.
    //  */
    // public function show($id)
    // {
    //     // $transaction = Transaction::with(['rental.pickupBranch', 'rental.returnBranch'])->find($id);

    //     // if (!$transaction) {
    //     //     return response()->json(['message' => 'Transaction not found'], 404);
    //     // }

    //     // return response()->json($transaction);
    //     $transaction = Transaction::with(['rental', 'user', 'pickupBranch', 'motor'])->findOrFail($id);

    //     return response()->json($transaction);
    // }

    // /**
    //  * Update the specified transaction.
    //  */
    // public function update(Request $request, $id)
    // {
    //     $transaction = Transaction::find($id);

    //     if (!$transaction) {
    //         return response()->json(['message' => 'Transaction not found'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'payment_method' => 'sometimes|string',
    //         'amount' => 'sometimes|numeric|min:0',
    //         'payment_status' => 'sometimes|string|in:pending,successful,failed',
    //         'start_date' => 'sometimes|date',
    //         'finish_date' => 'sometimes|date|after_or_equal:start_date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $transaction->update($request->all());

    //     return response()->json(['message' => 'Transaction updated successfully.', 'data' => $transaction]);
    // }

    // /**
    //  * Remove the specified transaction.
    //  */
    // public function destroy($id)
    // {
    //     $transaction = Transaction::find($id);

    //     if (!$transaction) {
    //         return response()->json(['message' => 'Transaction not found'], 404);
    //     }

    //     $transaction->delete();

    //     return response()->json(['message' => 'Transaction deleted successfully.']);
    // }
}
