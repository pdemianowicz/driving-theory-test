<?php

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Route::get('/user/{userId}', [UserController::class, 'show']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/categories', [TestController::class, 'index']);
Route::post('/test/{category}/start', [TestController::class, 'start']);
Route::get('/test/{uuid}/questions', [TestController::class, 'questions'])->middleware('CheckTestCompletion');
Route::post('/test/{uuid}/answer', [TestController::class, 'submitAnswer']);
Route::post('/test/{uuid}/finish', [TestController::class, 'finishTest']);
Route::get('/test/{uuid}/results', [TestController::class, 'resultsTest']);

Route::get('/media/{filename}', [TestController::class, 'showMedia']);
