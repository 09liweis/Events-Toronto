<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
  public function index()
  {
    return [
      "status" => 1,
      "data" => []
    ];
  }
}
