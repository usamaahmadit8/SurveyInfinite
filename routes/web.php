<?php

use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
Route::get('child_message', [WhatsappController::class, 'ChildTest']);
Route::get('child_count', [WhatsappController::class, 'ChildTest']);
Route::post('cerpApi', [WhatsappController::class, 'cerpApi']);
