<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/handleWebhook', [WebhookController::class, 'handleWebhook']);
Route::post('/generateRandomUser', [WebhookController::class, 'generateRandomUser']);