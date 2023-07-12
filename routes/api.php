<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LedgerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Product ledger report routes
Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');

//ledger report
Route::post('reports/sales', [LedgerController::class, 'reportSales'])->name('reports.sales');
Route::get('products/sales', [LedgerController::class, 'productSaleDetails'])->name('products.sales');