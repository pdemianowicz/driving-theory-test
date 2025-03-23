<?php

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [TestController::class, 'getCategories']);
Route::post('/initTest', [TestController::class, 'initTest']);
Route::post('/{test}/{questionOrder}/answer', [TestController::class, 'answerQuestion']);
Route::post('/{test}/finish', [TestController::class, 'finishTest']);
Route::get('/{test}/results', [TestController::class, 'getTestResult']);
