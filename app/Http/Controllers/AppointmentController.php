<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::user();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $appointment = Appointment::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time)->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString(),
            'end_time' => Carbon::parse($request->end_time)->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString(),
            'user_id' => $userId->id,
        ]);

        return response()->json($appointment, 201);
    }

    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        
        $appointment = Appointment::where('user_id', $user->id)->get();
        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $appointment->update($validatedData);
        $appointment->save();

        return response()->json($appointment);
    }


    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted']);
    }

    public function upcomingReminders()
{
    $now = now();
    $reminderTime = $now->addMinutes(5);

    $appointments = Appointment::whereBetween('end_time', [$now, $reminderTime])->get();

    return response()->json($appointments);
}

}
