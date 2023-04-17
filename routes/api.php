<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Rp76\Guzzle\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('coin/history', function (Request $request) {
    $client = new Client([
        'timeout' => 5.0,
        'proxy' => 'socks5://127.0.0.1:1060'
    ]);

    return $client->easySend(new \GuzzleHttp\Psr7\Request('POST', 'https://api.arzdigital.com/history/'), [
        "form_params" => array_filter([
            'action' => 'arzajax2',
            'gethistory' => $request->input('coin_id'),
            'range' => $request->input('range'),
            'dollar' => $request->has('dollar')
        ]),
    ])->json;
});
