<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CompanyController;

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
    dd($request->user());
});

    Route::get('companies/user/{id}', [CompanyController::class, 'getCompanyProducts'])->name('companies.products');

    Route::apiResources([
        'users' => UserController::class,
        'products' => ProductController::class,
        'companies' => CompanyController::class
    ]);
