<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
Route::resource('users', UserController::class);
Route::resource('audits', AuditController::class);
Route::resource('staff', StaffController::class);
Route::get('authenticated', [UserController::class, 'Authenticated']);
Route::post('complaintclose/{id}', [ComplaintController::class,'close']);
Route::get('mycomplaints', [ComplaintController::class,'Complaints']);
Route::get('businesscomplaint/{id}', [ComplaintController::class,'BusinessComp']);
Route::get('countcomp', [ComplaintController::class,'Compcount']);
Route::get('compcount/{id}', [ComplaintController::class,'CountComp']);
Route::get('thiscomplaint/{id}', [ComplaintController::class,'Thiscomplaint']);
Route::get('userstaff', [StaffController::class,'userstaff']);
Route::post('enrolstaff/{id}', [StaffController::class,'enrolstaff']);
Route::post('logout', [UserController::class,'logout']);
Route::resource('business', BusinessController::class);
Route::resource('complaints', ComplaintController::class);
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
Route::resource('dashboard', DashboardController::class);
Route::resource('audits', AuditController::class);

});

// Auth::routes();

Route::post('resetpassword', [UserController::class,'reset']);
Route::post('bbb', [UserController::class,'updatepass']);
Route::post('/login', [UserController::class, 'Login']);
Route::post('submitcode', [UserController::class,'codesub']);
