<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/events', [EventController::class, 'index']);