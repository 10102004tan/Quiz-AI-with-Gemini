<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class RoomApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get room
        $room = Room::find($id);
        // Get room join user from room

        // Check if room exist
        if (!$room) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        $room->joinedUsers;
        return response()->json([
            'room' => $room,
        ]);
    }

    public function initRoomPoint(string $id, Request $request)
    {
        // Get room
        $room = Room::find($id);
        // Get room join user from room

        // Check if room exist
        if (!$room) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        // Get room join user from room
        $room->joinedUsers;

        // Create room point
        foreach ($room->joinedUsers as $user) {
            RoomPoint::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'points' => 0,
            ]);
        }

        $user = Auth::guard('web')->user();
        $data['title'] = "Start Room";
        $data['content'] = "$user->name just start room";
        $data['user'] = $user;

        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('UserStartRoom', 'send-notify', $data);

        return response()->json([
            'status' => "success",
            'room' => $room,
        ]);
    }

    public function getQuestion(string $id)
    {
        // Get room
        $room = Room::where('room_id', $id)->first();
        // Get room join user from room

        // Check if room exist
        if (!$room) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        // Get room join user from room
        $room->joinedUsers;

        // Get quizz by quizz id
        $questions = $room->quiz->questions;
        foreach ($questions as $key => $ques) {
            $questions[$key]->answers;
        }

        return response()->json([
            "status"=> "success",
            'room' => $room,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}