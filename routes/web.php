<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilesController;
use App\http\Controllers\Pop_FilesController;
use App\http\Controllers\Pop_clients_filesController;
use App\http\Controllers\Ihm_bkpController;
use App\http\Controllers\Production_dataController;
use App\http\Controllers\View_production_dataController;
use App\http\Controllers\Search_production_dataController;
use App\http\Controllers\ShowPDFController;
use App\http\Controllers\ShowJSONController;
use App\http\Controllers\ShowExcelController;
use App\http\Controllers\ClpWegController;
use App\http\Controllers\ClpAltusController;
use App\http\Controllers\ClpAbbController;
use App\http\Controllers\Images_bkpController;
use App\http\Controllers\pop_bkpController;
use App\http\Controllers\pop_manut_bkpController;
use App\http\Controllers\pop_oper_bkpController;
use App\http\Controllers\Clients_filesController;
use App\http\Controllers\ClientsController;
use App\http\Controllers\SystemsController;
use App\http\Controllers\UsersController;
use App\http\Controllers\TelemetryController;
use App\http\Controllers\HomeController;

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

Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('files', [FilesController::class, 'index'])->name('files');
Route::get('files/{file}/download', [FilesController::class, 'download'])->name('files.download');
Route::post('files', [FilesController::class, 'destroy'])->name('files');
Route::delete('files/{id}', [FilesController::class, 'destroy'])->name('files');

Route::get('clients_files', [Clients_filesController::class, 'index'])->name('clients_files');
Route::get('clients_files/{file}/download', [Clients_filesController::class, 'download'])->name('clients_files.download');
Route::post('clients_files', [Clients_filesController::class, 'store'])->name('clients_files');
Route::delete('clients_files/{id}', [Clients_filesController::class, 'destroy'])->name('clients_files');

Route::get('pop_files', [Pop_FilesController::class, 'index'])->name('pop_files');
Route::get('pop_files/{file}/download', [Pop_FilesController::class, 'download'])->name('pop_files.download');
Route::post('pop_files', [Pop_FilesController::class, 'destroy'])->name('pop_files');
Route::delete('pop_files/{id}', [Pop_FilesController::class, 'destroy'])->name('pop_files');

Route::get('pop_clients_files', [Pop_clients_filesController::class, 'index'])->name('pop_clients_files.index');
Route::get('pop_clients_files/{file}/download', [Pop_clients_filesController::class, 'download'])->name('pop_clients_files.download');
Route::post('pop_clients_files', [Pop_clients_filesController::class, 'store'])->name('pop_clients_files.store');
Route::delete('pop_clients_files/{id}', [Pop_clients_filesController::class, 'destroy'])->name('pop_clients_files.destroy');

Route::get('clients', [ClientsController::class, 'index'])->name('clients');
Route::post('clients', [ClientsController::class, 'store'])->name('clients');
Route::delete('clients/{id}', [ClientsController::class, 'destroy'])->name('clients');
Route::get('edit_client/{id}', [ClientsController::class, 'edit'])->name('edit_client');
Route::put('edit_client/{id}', [ClientsController::class, 'update'])->name('update_client');

Route::get('systems', [SystemsController::class, 'index'])->name('systems');
Route::post('systems', [SystemsController::class, 'store'])->name('systems');
Route::delete('systems/{id}', [SystemsController::class, 'destroy'])->name('systems');
Route::get('edit_system/{id}', [SystemsController::class, 'edit'])->name('edit_system');
Route::put('edit_system/{id}', [SystemsController::class, 'update'])->name('update_system');

Route::get('users', [UsersController::class, 'index'])->name('users');
Route::post('users', [UsersController::class, 'store'])->name('users');
Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users');

Route::get('edit_user/{id}', [UsersController::class, 'edit'])->name('edit_user');
Route::put('edit_user/{id}', [UsersController::class, 'update'])->name('update_user');

Route::get('telemetry', [TelemetryController::class, 'index'])->name('telemetry');
Route::post('telemetry', [TelemetryController::class, 'store'])->name('telemetry');

Route::get('images_bkp', [Images_bkpController::class, 'index'])->name('images_bkp');
Route::post('images_bkp', [Images_bkpController::class, 'store'])->name('images_bkp');

Route::get('pop_bkp', [pop_bkpController::class, 'index'])->name('pop_bkp');
Route::post('pop_bkp', [pop_bkpController::class, 'store'])->name('pop_bkp');

Route::get('pop_manut_bkp', [pop_manut_bkpController::class, 'index'])->name('pop_manut_bkp');
Route::post('pop_manut_bkp', [pop_manut_bkpController::class, 'store'])->name('pop_manut_bkp');

Route::get('pop_oper_bkp', [pop_oper_bkpController::class, 'index'])->name('pop_oper_bkp');
Route::post('pop_oper_bkp', [pop_oper_bkpController::class, 'store'])->name('pop_oper_bkp');

Route::get('clp_weg', [ClpWegController::class, 'index'])->name('clp_weg');
Route::post('clp_weg', [ClpWegController::class, 'store'])->name('clp_weg');

Route::get('clp_altus', [ClpAltusController::class, 'index'])->name('clp_altus');
Route::post('clp_altus', [ClpAltusController::class, 'store'])->name('clp_altus');

Route::get('clp_abb', [ClpAbbController::class, 'index'])->name('clp_abb');
Route::post('clp_abb', [ClpAbbController::class, 'store'])->name('clp_abb');

Route::get('ihm_bkp', [Ihm_bkpController::class, 'index'])->name('ihm_bkp');
Route::post('ihm_bkp', [Ihm_bkpController::class, 'store'])->name('ihm_bkp');

Route::get('production_data', [Production_dataController::class, 'index'])->name('production_data');
Route::post('production_data', [Production_dataController::class, 'store'])->name('production_data');
Route::delete('production_data/{id}', [Production_dataController::class, 'destroy'])->name('production_data');

Route::get('view_production_data', [View_production_dataController::class, 'index'])->name('view_production_data');
Route::get('view_production_data/{file}/download', [View_production_dataController::class, 'download'])->name('view_production_data.download');
Route::post('view_production_data', [View_production_dataController::class, 'destroy'])->name('view_production_data');
Route::delete('view_production_data/{id}', [View_production_dataController::class, 'destroy'])->name('view_production_data');

Route::get('search_production_data', [Search_production_dataController::class, 'index'])->name('search_production_data');
Route::get('search_production_data/{file}/download', [Search_production_dataController::class, 'download'])->name('search_production_data.download');
Route::post('search_production_data', [Search_production_dataController::class, 'store'])->name('search_production_data');
Route::delete('search_production_data/{id}', [Search_production_dataController::class, 'destroy'])->name('search_production_data');

Route::get(uri: 'showpdf/view/{id}', action: [ShowPDFController::class, 'showPDF'])->name('showpdf.view');
Route::get('showjson/view/{id}', [ShowJSONController::class, 'showJSON'])->name('showjson.view');
Route::get('showexcel/view/{id}', [ShowExcelController::class, 'showExcel'])->name('showexcel.view');

