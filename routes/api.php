<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BranchesController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\MotorController;
use App\Http\Controllers\Api\TransactionController;


/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'getUserProfile']);
    Route::post('profile/update', [ProfileController::class, 'updateProfile']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/rental', [RentalController::class, 'store']);
    Route::get('/rental/show', [RentalController::class, 'show']);
});


Route::middleware('auth:api')->group(function () {
    Route::get('branches', [BranchesController::class, 'index']); // Fetch all branches
    Route::get('branches/{branch_id}', [BranchesController::class, 'show']); // Fetch a single branch
});

Route::middleware('auth:api')->group(function () {
    Route::get('motors', [MotorController::class, 'index']); // Fetch all motors
    Route::get('motors/{id}', [MotorController::class, 'show']); // Fetch a single motor
});

Route::middleware('auth:api')->group(function () {
    Route::get('transaction', [TransactionController::class, 'index']); // Ambil semua transaksi
    Route::get('transaction/{id}', [TransactionController::class, 'getTransactionSummary']); // Ambil detail transaksi
    Route::post('transaction', [TransactionController::class, 'createTransaction']); // Buat transaksi baru
    Route::put('transaction/{id}/payment-status', [TransactionController::class, 'updatePaymentStatus']); // Update status pembayaran
});

// Route::middleware('auth:api')->group(function () {
//     Route::post('/profile/complete', [RegisterController::class, 'completeProfile']);
// });

// Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->middleware('auth:api');
// Route::get('/user/profile', [ProfileController::class, 'getUserProfile'])->middleware('auth:api');

// Route::middleware('auth:api')->group(function () {
//     Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'getUserProfile'])->name('getUserProfile');
//     Route::post('/profile/update', [App\Http\Controllers\Api\ProfileController::class, 'updateProfile']);
// });

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');