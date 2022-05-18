<?php

use App\Http\Controllers\Custom\ShortResponse;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\ResetPassword;
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
    Route::get('/roles/{role:name}', 'usersByRole')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::json(Role::all()));
    Route::post('/roles/new', 'create')->middleware('auth:sanctum', 'abilities:role-admin');
    Route::patch('/roles/{role}', 'update')->whereNumber('role')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::json(Role::all()));
    Route::delete('/roles/{role}', 'delete')->whereNumber('role')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Role not found'));
});

// User Routing
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users')->middleware('auth:sanctum', 'ability:role-admin');
    Route::get('/users/me', 'getSelf')->middleware('auth:sanctum');
    Route::get('/users/{userid:id}', 'userById')->whereUuid('userid')->middleware('auth:sanctum', 'ability:role-admin')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::post('/users/register', 'register');
    Route::post('/users/register/admin', 'registerAdmin');//->middleware('auth:sanctum', 'abilities:role-admin');
    Route::post('/users/login', 'login');
    Route::patch('/users/{user:id}/role', 'editRole')->whereUuid('user')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::patch('/users/{user:id}/edit', 'update')->whereUuid('user')->middleware('auth:sanctum')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::patch('/users/{user:id}/password', 'changePassword')->whereUuid('user')->middleware('auth:sanctum')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::delete('/users/{user:id}', 'delete')->whereUuid('user')->middleware('auth:sanctum');
});

// Reset password
Route::controller(ResetPassword::class)->group(function () {
    Route::get('/password/view', 'example')->name('password.reset');
    Route::post('/password/forgot', 'resetLink');
    Route::post('/reset-password', 'resetPassword');
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
    Route::post('/locations', 'create')->middleware('auth:sanctum', 'ability:role-admin');
    Route::patch('/locations/{location}', 'update')->whereNumber('location')->middleware('auth:sanctum', 'ability:role-admin')->missing(fn() => ShortResponse::errorMessage('Location not found'));
    Route::delete('/locations/{location}', 'delete')->whereNumber('location')->middleware('auth:sanctum', 'ability:role-admin')->missing(fn() => ShortResponse::errorMessage('Location not found'));
});

// Fridge mode
Route::controller(ModeController::class)->group(function () {
    Route::get('/modes', 'index');
    Route::get('/modes/{id}', 'allFridgeByMode')->whereNumber('id')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
    Route::post('/modes', 'create')->middleware('auth:sanctum', 'ability:role-admin');
    Route::patch('/modes/{mode}', 'update')->whereNumber('mode')->middleware('auth:sanctum', 'ability:role-admin')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
    Route::delete('/modes/{mode}', 'delete')->whereNumber('mode')->middleware('auth:sanctum', 'ability:role-admin')->missing(fn() => ShortResponse::errorMessage('Mode not found'));
});

// Fridge
Route::controller(FridgeController::class)->group(function () {
    Route::get('/fridges', 'index');
    Route::get('/fridges/locations', 'withLocation');
    Route::get('/fridges/locations/{fridge:location_id}', 'byLocationId')->whereNumber('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::get('/fridges/{fridge}', 'fridgeById')->whereUuid('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::post('/fridges', 'create')->middleware('auth:sanctum', 'abilities:role-admin');
    Route::patch('/fridges/{fridge}', 'update')->whereUuid('fridge')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::delete('/fridges/{fridge}', 'delete')->whereUuid('fridge')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));

    Route::get('/fridges/{fridge}/tfid', 'tfid')->whereUuid('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::post('/fridges/qr', 'idByTfid')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
});


/*
 *
 *
 * Products route
 *
 *
 */

// Product
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{product}', 'productById')->whereNumber('product')->missing(fn() => ShortResponse::errorMessage('Product not found'));
    Route::post('/products', 'create')->middleware('auth:sanctum', 'abilities:role-admin');
    Route::patch('/products/{product}', 'update')->whereNumber('product')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Product not found'));
    Route::delete('/products/{product}', 'delete')->whereNumber('product')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Product not found'));
});

// Fridge warehouse
Route::controller(WarehouseController::class)->group(function () {
    Route::get('/warehouse/{fridge:id}', 'index')->whereUuid('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge or Warehouse not found'));
    Route::get('/warehouse/{fridge:id}/info', 'indexIncludeInfo')->whereUuid('fridge')->missing(fn() => ShortResponse::errorMessage('Fridge or Warehouse not found'));

    Route::post('/warehouse', 'create');

// Do comment route below in production mode
    Route::delete('/warehouse/{fridge:id}/fresh', 'fresh')->whereUuid('fridge')->middleware('auth:sanctum', 'abilities:role-admin')->missing(fn() => ShortResponse::errorMessage('Fridge or Warehouse not found'));
});

Route::controller(OperationController::class)->group(function () {
    Route::get('/operations', 'index')->middleware('auth:sanctum');
    Route::get('/operations/user/{user:id}', 'byUserId')->whereUuid('user')->middleware('auth:sanctum')->missing(fn() => ShortResponse::errorMessage('User not found'));
    Route::get('/operations/detail/{operation}', 'operationDetail')->whereNumber('operation')->middleware('auth:sanctum')->missing(fn() => ShortResponse::errorMessage('Operation not found'));

    Route::post('/operations/fridge/new', 'createOperation');
});

Route::controller(QRController::class)->group(function () {
    Route::get('/qr/get/{fridge:id}', 'getQR')->missing(fn() => ShortResponse::errorMessage('Fridge not found'));
    Route::post('/qr/{fridge:tfid}', 'scanQR')->missing(fn() => ShortResponse::errorMessage('Invalid QR data'));
});
