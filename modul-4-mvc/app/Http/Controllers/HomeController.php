<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;

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
        $userId = auth()->id();

        $query = Booking::whereHas('group.users', fn($q) => $q->where('user_id', $userId));
        $total = $query->count();
        $approved = (clone $query)->where('status', 'approved')->count();
        $rejected = (clone $query)->where('status', 'rejected')->count();
        $latestNotification = Notification::where('user_id', $userId)->latest()->first();

        return view('student', compact('total', 'approved', 'rejected', 'latestNotification'));
    }

    public function admin()
    {
        $total = Booking::count();
        $approved = Booking::where('status', 'approved')->count();
        $rejected = Booking::where('status', 'rejected')->count();
        $pendingBookings = Booking::where('status', 'pending')->with('group', 'room')->latest()->take(5)->get();

        return view('admin', compact('total', 'approved', 'rejected', 'pendingBookings'));
    }
}
