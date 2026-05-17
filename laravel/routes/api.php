<?php

declare(strict_types=1);

use App\Http\Controllers\CodeController;
use Illuminate\Support\Facades\Route;

Route::get('codes', [CodeController::class, 'index']);
Route::post('codes', [CodeController::class, 'store']);
Route::get('codes/{codeType}', [CodeController::class, 'listByType']);
Route::get('codes/{codeType}/{code}', [CodeController::class, 'show'])->where('code', '[0-9]+');
Route::put('codes/{codeType}/{code}', [CodeController::class, 'update'])->where('code', '[0-9]+');
Route::delete('codes/{codeType}/{code}', [CodeController::class, 'destroy'])->where('code', '[0-9]+');
