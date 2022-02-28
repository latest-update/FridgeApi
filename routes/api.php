<?php

use App\Http\Controllers\Custom\ShortResponse;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 *
 *
 * Users Routes Area
 *
 *
 */

// Roles Routing
Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'roles');
    Route::get('/roles/{role:name}', 'usersByRole')->missing(fn() => ShortResponse::json(false, 'Role not found', Role::all()));
    Route::post('/roles/new', 'create');
    Route::patch('/roles/{role}', 'update')->missing(fn() => ShortResponse::json(false, 'Role not found', Role::all()));
    Route::delete('/roles/{id}', 'delete');
});

// User Routing
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users');
    Route::get('/users/{user:login}', 'userByLogin')->whereAlpha('user')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::get('/users/{userid:id}', 'userById')->whereNumber('userid')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::post('/users/new', 'create');
    Route::patch('/users/{user:id}/role', 'editRole')->whereNumber('user')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::patch('/users/{user:id}/edit', 'update')->whereNumber('user')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::patch('/users/{user:id}/password', 'changePassword')->whereNumber('user')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::delete('/users/{id}', 'delete');
});

/*
 *
 *
 * Location of the fridge routes
 *
 *
 */

Route::controller(LocationController::class)->group(function () {
    Route::get('/locations', 'index');
    Route::get('/locations/{city}', 'locationByCity');
    Route::post('/locations', 'create');
    Route::patch('/locations/{location}', 'update')->whereNumber('id')->missing(fn() => ShortResponse::errorMessage('Location not found'));
    Route::delete('/locations/{location}', 'delete')->whereNumber('id')->missing(fn() => ShortResponse::errorMessage('Location not found'));
});
