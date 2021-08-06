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



Route::get('companies/user/{id}', [CompanyController::class, 'getCompanyProducts'])->name('companies.products');

Route::post('companies/selected', [CompanyController::class, 'deleteSelectedCompanies'])->name('companies.selected');
Route::post('products/selected', [ProductController::class, 'deleteSelectedProducts'])->name('products.selected');
Route::post('users/selected', [UserController::class, 'deleteSelectedUsers'])->name('users.selected');

Route::apiResources([
    'users' => UserController::class,
    'products' => ProductController::class,
    'companies' => CompanyController::class
]);
