<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function rooms(Request $request)
    {
        $search = $request->input('search');

        $totalCount = Room::count();

        $rooms = Room::when($search, function ($query, $search) {
                $query->where('room_name', 'like', "%{$search}%")
                    ->orWhere('room_building', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.room.rooms', compact('rooms', 'search', 'totalCount'));
    }

    public function createRoom()
    {
        return view('admin.room.create-room');
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'room_name'     => 'required|string|max:255',
            'room_building' => 'required|string|max:255',
        ]);

        Room::create($request->only(['room_name', 'room_building']));

        return redirect()->route('admin.rooms.index')->with('success', 'Room added successfully.');
    }

    public function editRoom(Room $room)
    {
        return view('admin.room.edit-room', compact('room'));
    }

    public function updateRoom(Request $request, Room $room)
    {
        $request->validate([
            'room_name'     => 'required|string|max:255',
            'room_building' => 'required|string|max:255',
        ]);

        $room->update($request->only(['room_name', 'room_building']));

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroyRoom(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted.');
    }
}