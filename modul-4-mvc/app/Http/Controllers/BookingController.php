<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->hasRole('admin')
            ? Booking::with(['group', 'room'])->latest()->get()
            : Booking::whereHas('group.users', fn($q) => $q->where('user_id', Auth::id()))
            ->with(['group', 'room'])->latest()->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::all();
        $groups = Auth::user()->groups;

        return view('bookings.create', compact('rooms', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $overlap = Booking::where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->exists();

        if ($overlap) {
            return back()->with('error', 'Time slot overlaps with existing booking.');
        }

        Booking::create([
            'group_id' => $request->group_id,
            'room_id' => $request->room_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking submitted.');
    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (!Auth::user()->hasRole('admin')) abort(403);
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (!Auth::user()->hasRole('admin')) abort(403);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected',
        ]);

        $booking->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        foreach ($booking->group->users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->status === 'approved' ? 'Booking Approved' : 'Booking Rejected',
                'message' => $request->status === 'approved'
                    ? "Your booking for {$booking->room->name} on {$booking->date} has been approved."
                    : "Your booking for {$booking->room->name} on {$booking->date} has been rejected. Reason: {$booking->rejection_reason}",
                'type' => 'booking',
                'is_read' => false,
            ]);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        if (!Auth::user()->hasRole('admin') && $booking->group->leader_id !== Auth::id()) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted.');
    }
}
