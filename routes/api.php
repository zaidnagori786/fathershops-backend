<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FatherShopsController;


Route::get('/categories', [FatherShopsController::class, 'categories']);

Route::get('/products/{id}', [FatherShopsController::class, 'products']);

Route::get('/products/details/{id}', [FatherShopsController::class, 'productDetails']);