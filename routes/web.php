<?php

use App\Http\Controllers\CategorieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\SubCategorieController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ViewsController;

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



Auth::routes(['verify' => true, 'remember_me' => false]);

Route::group(['middleware' => ['auth', 'verified', 'log', 'throttle:web']], function () {
    Route::get('/', function () {
        return view('home');
    })->middleware('permission:tickets');
    //Route::group(['middleware' => ['has.role:Administrador']], function () {
    // Apis

    Route::resource('/api/department', DepartmentController::class)->middleware('permission:departments');
    Route::resource('/api/municipality', MunicipalityController::class)->middleware('permission:municipalities');
    Route::resource('/api/categorie', CategorieController::class)->middleware('permission:categories');
    Route::resource('/api/sub_categorie', SubCategorieController::class)->middleware('permission:sub_categories');;
    Route::resource('/api/ticket', TicketController::class)->middleware('permission:tickets');;
    Route::resource('/api/user', UserController::class)->middleware('permission:users');
    Route::resource('/api/role', RoleController::class)->middleware('permission:roles');

    // Views
    Route::get('/departments', function () {
        return view('department.index');
    })->middleware('permission:departments');

    Route::get('/municipalities', function () {
        return view('municipality.index');
    })->middleware('permission:municipalities');

    Route::get('/categories', function () {
        return view('categorie.index');
    })->middleware('permission:categories');

    Route::get('/sub_categorie', function () {
        return view('sub_categorie.index');
    })->middleware('permission:sub_categorie');

    Route::get('/tickets', function () {
        return view('ticket.index');
    })->middleware('permission:tickets');

    Route::get('/users', function () {
        return view('user.index');
    })->middleware('permission:users');
    //});

    Route::put('/api/ticket', [TicketController::class, 'update'])->middleware('permission:update-tickets');
    Route::get('/api/ticket', [TicketController::class, 'index'])->middleware('permission:tickets');
    Route::post('/api/ticket', [TicketController::class, 'store'])->middleware('permission:create-tickets');
    Route::delete('/api/ticket', [TicketController::class, 'destroy'])->middleware('permission:delete-tickets');

    //Reports
    Route::get('generate-pdf', [PDFController::class, 'generatePDF']);

    //Excel
    Route::get('export', [ExcelController::class, 'export']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/api/categorie', CategorieController::class);
    Route::delete('/api/categorie', [CategorieController::class, 'destroy']);

    Route::resource('/api/subCategorie', SubCategorieController::class);
    Route::delete('/api/subCategorie', [SubCategorieController::class, 'destroy']);

    Route::resource('/api/permission', PermissionController::class)->middleware('permission:permissions');
    Route::delete('/api/permission', [PermissionController::class, 'destroy'])->middleware('permission:permissions');
    Route::get('/roles', [ViewsController::class, 'roles'])->middleware('permission:roles');
});

Route::post('import', [ExcelController::class, 'import']);
