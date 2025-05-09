<?php

//base
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//admin
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\SystemsController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\SectorsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
//upload
use App\Http\Controllers\Upload\UploadSetpointsController;
use App\Http\Controllers\Upload\UploadTelemetryController;
use App\Http\Controllers\Upload\UploadIhmController;
use App\Http\Controllers\Upload\UploadPopController;
use App\Http\Controllers\Upload\UploadClpController;
use App\Http\Controllers\Upload\UploadXlsxController;
use App\Http\Controllers\Upload\UploadPdfController;
//show
use App\Http\Controllers\show\ShowPdfController;
use App\Http\Controllers\show\ShowJsonController;
use App\Http\Controllers\show\ShowExcelController;
//view
use App\Http\Controllers\View\FilesController;
use App\Http\Controllers\View\SearchFilesController;


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

//admin
Route::get('clients', [ClientsController::class, 'index'])->name('clients');
Route::post('clients', [ClientsController::class, 'store'])->name('clients');
Route::delete('clients/{id}', [ClientsController::class, 'destroy'])->name('clients');
Route::get('edit_client/{id}', [ClientsController::class, 'edit'])->name('edit_client');
Route::put('edit_client/{id}', [ClientsController::class, 'update'])->name('update_client');

Route::get('users', [UsersController::class, 'index'])->name('users');
Route::post('users', [UsersController::class, 'store'])->name('users');
Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users');
Route::get('edit_user/{id}', [UsersController::class, 'edit'])->name('edit_user');
Route::put('edit_user/{id}', [UsersController::class, 'update'])->name('update_user');

Route::get('systems', [SystemsController::class, 'index'])->name('systems');
Route::post('systems', [SystemsController::class, 'store'])->name('systems');
Route::delete('systems/{id}', [SystemsController::class, 'destroy'])->name('systems');
Route::get('edit_system/{id}', [SystemsController::class, 'edit'])->name('edit_system');
Route::put('edit_system/{id}', [SystemsController::class, 'update'])->name('update_system');

Route::get('types', [TypesController::class, 'index'])->name('types');
Route::post('types', [TypesController::class, 'store'])->name('types');
Route::delete('types/{id}', [TypesController::class, 'destroy'])->name('types');
Route::get('edit_type/{id}', [TypesController::class, 'edit'])->name('edit_type');
Route::put('edit_type/{id}', [TypesController::class, 'update'])->name('edit_type');

Route::get('sectors', [SectorsController::class, 'index'])->name('sectors');
Route::post('sectors', [SectorsController::class, 'store'])->name('sectors');
Route::delete('sectors/{id}', [SectorsController::class, 'destroy'])->name('sectors');
Route::get('edit_sector/{id}', [SectorsController::class, 'edit'])->name('edit_sector');
Route::put('edit_sector/{id}', [SectorsController::class, 'update'])->name('edit_sector');

Route::get('profiles', [ProfilesController::class, 'index'])->name('profiles');
Route::post('profiles', [ProfilesController::class, 'store'])->name('profiles');
Route::delete('profiles/{id}', [ProfilesController::class, 'destroy'])->name('profiles');
Route::get('edit_profile/{id}', [ProfilesController::class, 'edit'])->name('edit_profile');
Route::put('edit_profile/{id}', [ProfilesController::class, 'update'])->name('edit_profile');


//uploads
Route::get('upload_telemetry', [UploadTelemetryController::class, 'index'])->name('upload_telemetry');
Route::post('upload_telemetry', [UploadTelemetryController::class, 'store'])->name('upload_telemetry');

Route::get('upload_setpoints', [UploadSetpointsController::class, 'index'])->name('upload_setpoints');
Route::post('upload_setpoints', [UploadSetpointsController::class, 'store'])->name('upload_setpoints');

Route::get('upload_ihm', [UploadIhmController::class, 'index'])->name('upload_ihm');
Route::post('upload_ihm', [UploadIhmController::class, 'store'])->name('upload_ihm');

Route::get('upload_pop/{sector}', [UploadPopController::class, 'index'])->name('uploads.upload_pop.index');
Route::post('upload_pop/{sector}', [UploadPopController::class, 'store'])->name('uploads.upload_pop.store');

Route::get('upload_clp/{model}', [UploadClpController::class, 'index'])->name('uploads.upload_clp.index');
Route::post('upload_clp/{model}', [UploadClpController::class, 'store'])->name('uploads.upload_clp.store');

Route::get('upload_xlsx/{type}', [UploadXlsxController::class, 'index'])->name('uploads.upload_xlsx.index');
Route::post('upload_xlsx/{type}', [UploadXlsxController::class, 'store'])->name('uploads.upload_xlsx.store');

Route::get('upload_pdf', [UploadPdfController::class, 'index'])->name('upload_pdf');
Route::post('upload_pdf', [UploadPdfController::class, 'store'])->name('upload_pdf');

//show
Route::get(uri: 'showpdf/view/{id}', action: [ShowPdfController::class, 'showPDF'])->name('showpdf.view');
Route::get('showjson/view/{id}', [ShowJsonController::class, 'showJSON'])->name('showjson.view');
Route::get('showexcel/view/{id}', [ShowExcelController::class, 'showExcel'])->name('showexcel.view');

//view
Route::get('files', [FilesController::class, 'index'])->name('files');
Route::get('files/{file}/download', [FilesController::class, 'download'])->name('files.download');
Route::post('files', [FilesController::class, 'destroy'])->name('files');
Route::delete('files/{id}', [FilesController::class, 'destroy'])->name('files');

Route::get('search_files', [SearchFilesController::class, 'index'])->name('search_files.index');
Route::get('search_files/{file}/download', [SearchFilesController::class, 'download'])->name('search_files.download');
Route::post('search_files', [SearchFilesController::class, 'store'])->name('search_files.store');
Route::delete('search_files/{id}', [SearchFilesController::class, 'destroy'])->name('search_files.destroy');

use App\Http\Controllers\TestDataController;

//Route::get('/teste', action: [TestDataController::class, 'store']);

Route::post('/teste', [TestDataController::class, 'store']);





