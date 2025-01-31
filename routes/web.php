<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Location\AutoAuthController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Auth\Auth2Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return redirect('/login');
})->name('home');
Route::prefix('authorization')->name('crm.')->group(function () {
    Route::get('/crm/oauth/callback', [Auth2Controller::class, 'crmCallback'])->name('oauth_callback');
});
Route::group(['middleware' => ['auth']], function () {
    Route::any('dashboard', [SettingController::class, 'home'])->name('dashboard');
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

        Route::any('dashboard', [SettingController::class, 'index'])->name('setting.index');
        Route::middleware('admin')->group(function () {
            Route::get('setting', [SettingController::class, 'index'])->name('setting');
            Route::post('/setting/save', [SettingController::class, 'save'])->name('setting.save');
            Route::get('voices', [SettingController::class, 'getVoices'])->name('get.voices');
        });
    });

    Route::group(['as' => 'location.', 'prefix' => 'location'], function () {
        Route::get('dashboard', [LocationController::class, 'index'])->name('setting.index');
        Route::get('voices/list', [LocationController::class, 'voices'])->name('get.all.voices');
        Route::get('voices/own', [LocationController::class, 'voices'])->name('get.own.voices');
    });
});


Route::get('check/auth', [AutoAuthController::class, 'connect'])->name('auth.check');
Route::get('check/auth/error', [AutoAuthController::class, 'authError'])->name('error');
Route::get('checking/auth', [AutoAuthController::class, 'authChecking'])->name('admin.auth.checking');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('voices/list/{type?}', [IndexController::class, 'getVoices'])->name('voices.list');
    Route::post('save/selected/voice', [IndexController::class, 'saveSelectedVoice'])->name('save.selected.voice');
    
});




require __DIR__.'/auth.php';
