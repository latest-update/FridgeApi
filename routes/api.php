<?php

use App\Http\Controllers\Custom\ShortResponse;
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
 * Users Routing Area
 *
 *
 */

// Roles Routing
Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'roles');
    Route::get('/roles/{role:name}', 'usersByRole')->missing(fn() => ShortResponse::json(false, 'Role not found', Role::all()));
    Route::post('/roles/new', 'create');
    Route::patch('/roles/{id}', 'update');
    Route::delete('/roles/{id}', 'delete');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users');
    Route::get('/users/{user:login}', 'userByLogin')->whereAlpha('user')->missing([UserController::class, 'userNotFound']);
    Route::get('/users/{userid:id}', 'userById')->whereNumber('userid')->missing([UserController::class, 'userNotFound']);
    Route::post('/users/new', 'create');
    Route::patch('/users/{user:id}/role', 'editRole')->whereNumber('user')->missing([UserController::class, 'userNotFound']);
    Route::patch('/users/{user:id}/edit', 'update')->whereNumber('user')->missing([UserController::class, 'userNotFound']);
    Route::patch('/users/{user:id}/password', 'changePassword')->whereNumber('user')->missing([UserController::class, 'userNotFound']);
    Route::delete('/users/{id}', 'delete');
});


