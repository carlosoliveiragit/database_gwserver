<?php

use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('home');
});

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('files', [App\Http\Controllers\FilesController::class, 'index'])->name('files');
Route::post('files', [App\Http\Controllers\FilesController::class, 'destroy'])->name('files');
Route::delete('files/{id}', [App\Http\Controllers\FilesController::class, 'destroy'])->name('files');

Route::get   ('clients',           [App\Http\Controllers\ClientsController::class, 'index'  ])->name('clients');
Route::post  ('clients',           [App\Http\Controllers\ClientsController::class, 'store'  ])->name('clients');
Route::delete('clients/{id}',      [App\Http\Controllers\ClientsController::class, 'destroy'])->name('clients');
Route::get   ('edit_client/{id}',  [App\Http\Controllers\ClientsController::class, 'edit'   ])->name('edit_client');
Route::put   ('edit_client/{id}',  [App\Http\Controllers\ClientsController::class, 'update' ])->name('update_client');

Route::get('systems', [App\Http\Controllers\SystemsController::class, 'index'])->name('systems');
Route::post('systems', [App\Http\Controllers\SystemsController::class, 'store'])->name('systems');
Route::delete('systems/{id}', [App\Http\Controllers\SystemsController::class, 'destroy'])->name('systems');
Route::get   ('edit_system/{id}',  [App\Http\Controllers\SystemsController::class, 'edit'   ])->name('edit_system');
Route::put   ('edit_system/{id}',  [App\Http\Controllers\SystemsController::class, 'update' ])->name('update_system');

Route::get('users', [App\Http\Controllers\UsersController::class, 'index'])->name('users');
Route::post('users', [App\Http\Controllers\UsersController::class, 'store'])->name('users');
Route::delete('users/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users');
Route::get   ('edit_user/{id}',  [App\Http\Controllers\UsersController::class, 'edit'   ])->name('edit_user');
Route::put   ('edit_user/{id}',  [App\Http\Controllers\UsersController::class, 'update' ])->name('update_user');

Route::get('telemetry', [App\Http\Controllers\TelemetryController::class, 'index'])->name('telemetry');
Route::post('telemetry', [App\Http\Controllers\TelemetryController::class, 'store'])->name('telemetry');

Route::get('clp_weg', [App\Http\Controllers\ClpWegController::class, 'index'])->name('clp_weg');
Route::post('clp_weg', [App\Http\Controllers\ClpWegController::class, 'store'])->name('clp_weg');

Route::get('clp_altus', [App\Http\Controllers\ClpAltusController::class, 'index'])->name('clp_altus');
Route::post('clp_altus', [App\Http\Controllers\ClpAltusController::class, 'store'])->name('clp_altus');

Route::get('clp_abb', [App\Http\Controllers\ClpAbbController::class, 'index'])->name('clp_abb');
Route::post('clp_abb', [App\Http\Controllers\ClpAbbController::class, 'store'])->name('clp_abb');



