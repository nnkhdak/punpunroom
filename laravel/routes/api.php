<?php

use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/codes', [CodeController::class, 'index']);
Route::get('/codes/{code_type}', [CodeController::class, 'index']);
Route::get('/codes/{code_type}/{code}', [CodeController::class, 'show']);
Route::post('/codes', [CodeController::class, 'store']);
Route::put('/codes/{code_type}/{code}', [CodeController::class, 'update']);
Route::delete('/codes/{code_type}/{code}', [CodeController::class, 'destroy']);

Route::get('/persons', [PersonController::class, 'index']);
Route::get('/persons/{id}', [PersonController::class, 'show']);
Route::post('/persons', [PersonController::class, 'store']);
Route::put('/persons/{id}', [PersonController::class, 'update']);
Route::delete('/persons/{id}', [PersonController::class, 'destroy']);

Route::get('/organizations', [OrganizationController::class, 'index']);
Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
Route::post('/organizations', [OrganizationController::class, 'store']);
Route::put('/organizations/{id}', [OrganizationController::class, 'update']);
Route::delete('/organizations/{id}', [OrganizationController::class, 'destroy']);
