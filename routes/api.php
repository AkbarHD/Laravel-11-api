<?php

use App\Http\Controllers\API\v1\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/list-articles', [ArticleController::class, 'index']);
    Route::post('/store-article', [ArticleController::class, 'store']);
    Route::get('/read-article/{id}', [ArticleController::class, 'show']);
    Route::put('/update-article/{id}', [ArticleController::class, 'update']);
});
