<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/v1/events', [EventController::class, 'index']);
Route::get('/api/v1/events/import',[EventController::class,'import']);