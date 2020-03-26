<?php

namespace App\Http\Controllers;
use App\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('home');
	}
	public function detail(int $id) {
		$event = Event::findOrFail($id);
		return view('event/detail',['event'=>$event]);
	}
}
