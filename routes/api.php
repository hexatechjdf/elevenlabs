<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Jobs\ProcessRefreshToken;

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
Route::post('webhook/text/to/speech', [WebhookController::class, 'convertTextToSpeech'])->name('webhook.text.speech')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/cron-jobs/process_refresh_token', function () {
    dispatch((new ProcessRefreshToken())->onQueue('refresh_token'));
});
