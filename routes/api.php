<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('/api/v1/')->group(function () {
  Route::get('events', [EventController::class, 'index']);
  Route::get('events/import',[EventController::class,'import']);
});