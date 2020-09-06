<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExposuresController;
use App\Http\Controllers\ObjectsController;

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
/*
Route::group(['middleware' => ['auth', 'verified']], function () {
    // User & Profile...
    Route::get('/user/exposures', [ExposuresController::class, 'index'])->name('exposures');    // User & Profile...
    Route::post('/user/exposures', [ExposuresController::class, 'store'])->name('exposures');

});
Route::group(['middleware' => ['auth', 'verified']], function () {
    // User & Profile...
    Route::get('/user/coverages', [CoveragesController::class, 'index'])->name('coverages');    // User & Profile...
    Route::post('/user/coverages', [CoveragesController::class, 'store'])->name('coverages.post');
    Route::delete('/user/coverages', [CoveragesController::class, 'delete'])->name('coverages.delete');

});
*/

Route::group(['middleware' => ['auth', 'verified']], function () {
    // User & Profile...

    Route::get('/dashboard/:model/:id', [ObjectsController::class, 'index'])->name('object');
    Route::put('/dashboard/:model/:id', [ObjectsController::class, 'update'])->name('object.update');
    Route::delete('/dashboard/:model/:id', [ObjectsController::class, 'delete'])->name('object.delete');
    Route::get('/dashboard/:model', [ObjectsController::class, 'index'])->name('objects');
    Route::post('/dashboard/:model', [ObjectsControeller::class, 'store'])->name('objects.post');

});
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
