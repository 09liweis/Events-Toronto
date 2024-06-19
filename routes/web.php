<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventController;

Route::get('/', function () {
  return view('welcome');
});

Route::prefix('/api/v1/')->group(function () {
  Route::get('events', [EventController::class, 'index']);
  Route::get('events/import',[EventController::class,'import']);
});