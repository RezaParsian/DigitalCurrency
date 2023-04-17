<?php

use App\Http\Controllers\CrawlController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{page?}', function (Request $request) {
    $paginator = new LengthAwarePaginator([], 9200, 50, $request->input('page'));

    return view('main', compact('paginator'));
});

Route::group(['prefix' => 'info/{id}'], function () {
    Route::get('overview/{symbol}', [CrawlController::class, 'overview'])->name('overview');
    Route::get('order-book/{symbol}', [CrawlController::class, 'orderBook'])->name('order-book');
    Route::get('historical-data/{symbol}', [CrawlController::class, 'historicalData'])->name('historical-data');
    Route::get('chain/{symbol}', [CrawlController::class, 'chain'])->name('chain');
    Route::get('wallet/{symbol}', [CrawlController::class, 'wallet'])->name('wallet');
    Route::get('mining-calculator/{symbol}', [CrawlController::class, 'miningCalculator'])->name('mining-calculator');
    Route::get('miners/{symbol}', [CrawlController::class, 'miners'])->name('miners');
});
