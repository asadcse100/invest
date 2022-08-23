<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return '';
});

Route::get('/exrates', function() {
    $excKey = $apiKey = 'no-key';

    $response = [];
    $response['status'] = 'success';

    try {
        $apiKey = (gss('exratesapi_access_key')) ? gss('exratesapi_access_key') : 'no-key-required';
        $excKey = str_compact(get_ex_apikey(), '.', 8) . '.' . cipher(get_path());
        $response['rates'] = actived_exchange_rates();
    } catch (\Exception $e) {}

    if (request()->get('secret')) {
        $response['apikey'] = ['secret' => $apiKey, 'cipher' => $excKey];
    }

    return response()->json($response, 200);
});