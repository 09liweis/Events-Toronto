<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    // return list of categories
    public function list() {
        return Category::select('id', 'name')->get();
    }
    
    public function events($id) {
        $category = Category::where('id', $id)->first();
        if ($category) {
            $events = $category->events()->get();
            return array(
                'category' => array(
                    'id' => $category->id,
                    'name' => $category->name
                ),
                'events' => $events
            );
        }
        else {
            return array(
                'msg' => 'No Category Found'
            );
        }
    }
}
