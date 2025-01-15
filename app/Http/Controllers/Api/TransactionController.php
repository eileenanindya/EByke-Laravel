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

    public function getUserTransactions($userId) {
        $transactions = Transactions::where('user_id', $userId)
            ->with('rental')
            ->orderBy('created_at', 'desc')
            ->get();
    
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'No transactions found.'], 404);
        }
    
        return response()->json($transactions, 200);
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            // 'amount' => 'required|numeric',
        ]);

        $rental = Rental::find($request->rental_id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found.'], 404);
        }

        $transaction = Transactions::create([
            'rental_id' => $rental->id,
            'user_id' => $rental->user_id,
            'payment_method' => $request->payment_method,
            'amount' => $rental->total_cost,
            'payment_status' => 'pending',
            'payment_time' => null,
        ]);

        return response()->json($transaction, 201);
    }

    public function getTransactionSummary($id)
    {
        $transaction = Transactions::with('user.profile','rental')->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        return response()->json($transaction, 200);
    }

    public function updateRentStatus(Request $request, $transactionId)
    {
        $request->validate([
            'status' => 'required|in:active,completed, cancelled',
        ]);

        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $rental = $transaction->rentals->first();

        if (!$rental) {
            return response()->json(['message' => 'Rental not found for this transaction'], 404);
        }

        $rental->status = 'completed';
        $transaction->save();

        return response()->json([
            'message' => 'Rental status updated successfully.',
            'rental' => $rental
        ]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|integer|exists:transactions,id',
            'payment_status' => 'required|in:pending,success,failed',
        ]);
    
        $transaction = Transactions::find($id);
    
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    
        $transaction->payment_status = $validated['payment_status'];
        $transaction->payment_time = $validated['payment_status'] === 'success' ? now() : null;
        $transaction->save();
    
        return response()->json([
            'message' => 'Payment status updated successfully.',
            'transaction' => $transaction,
        ], 200);
    }
}
