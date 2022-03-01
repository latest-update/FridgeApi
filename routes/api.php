<?php

use App\Http\Controllers\Custom\ShortResponse;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
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
 * Fridge routes
 *
 *
 */

// Location
Route::controller(LocationController::class)->group(function () {
    Route::get('/locations', 'index');
    Route::get('/locations/fridges', 'withFridge');
    Route::get('/locations/{city}', 'locationByCity')->missing(fn() => ShortResponse::errorMessage('Location not found'));
    Route::post('/locations', 'create');
    Route::patch('/locations/{location}', 'update')->whereNumber('location')->missing(fn() => ShortResponse::errorMessage('Location not found'));
    Route::delete('/locations/{location}', 'delete')->whereNumber('location')->missing(fn() => ShortResponse::errorMessage('Location not found'));
});

// Fridge mode
Route::controller(ModeController::class)->group(function () {
    Route::get('/modes', 'index');
    Route::get('/modes/{id}', 'allFridgeByMode')->whereNumber('id')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
    Route::post('/modes', 'create');
    Route::patch('/modes/{mode}', 'update')->whereNumber('mode')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
    Route::delete('/modes/{mode}', 'delete')->whereNumber('mode')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
});

// Fridge
Route::controller(FridgeController::class)->group(function () {
    Route::get('/fridges', 'index');
    Route::get('/fridges/locations', 'withLocation');
    Route::get('/fridges/locations/{fridge:location_id}', 'byLocationId')->whereNumber('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::get('/fridges/{fridge}', 'fridgeById')->whereNumber('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::post('/fridges', 'create');
    Route::patch('/fridges/{fridge}', 'update')->whereNumber('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::delete('/fridges/{fridge}', 'delete')->whereNumber('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
});


/*
 *
 *
 * Products route
 *
 *
 */

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{product}', 'productById')->whereNumber('product')->missing(fn() => ShortResponse::errorMessage('Product not found'));
    Route::post('/products', 'create');
    Route::patch('/products/{product}', 'update')->whereNumber('product')->missing(fn() => ShortResponse::errorMessage('Product not found'));
    Route::delete('/product{product}', 'delete')->missing(fn() => ShortResponse::errorMessage('Product not found'));;
});

Route::controller(WarehouseController::class)->group(function () {
    Route::get('/warehouse/{id}', 'index')->whereNumber('id')->missing(fn() => ShortResponse::errorMessage('Fridge or Warehouse not found'));
    Route::get('/warehouse/{warehouse}/info', 'indexIncludeInfo')->whereNumber('warehouse')->missing(fn() => ShortResponse::errorMessage('Fridge or Warehouse not found'));
    Route::post('/warehouse', 'create');
});

