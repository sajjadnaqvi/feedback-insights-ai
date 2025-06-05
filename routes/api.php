<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/webhook/receive', [App\Http\Controllers\WebhookController::class, 'handle']);
// Route::get('/webhook/receive', function(){
//     return response()->json(['message' => 'welcome'], 200);
// });

