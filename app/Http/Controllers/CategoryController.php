<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function list() {
        return Category::select('id', 'name')->get();
    }
    
    public function events($id) {
        
    }
}
