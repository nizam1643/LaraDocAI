<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin');
        } elseif (auth()->user()->hasRole('student')) {
            return redirect()->route('student');
        } else {
            return redirect()->route('home');
        }
    }

    public function student()
    {
        return view('student');
    }

    public function admin()
    {
        return view('admin');
    }
}
