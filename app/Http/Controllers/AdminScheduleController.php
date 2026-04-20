<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class AdminScheduleController extends Controller
{
    public function index()
    {
        // Otomatis tandai jadwal yang sudah melewati batas presensi sebagai 'completed'
        \App\Models\Schedule::where('status', 'active')->each(function ($schedule) {
            if ($schedule->isExpired()) {
                $schedule->update(['status' => 'completed']);
            }
        });

        $schedules = Schedule::orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal latihan berhasil ditambahkan.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal latihan berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal latihan berhasil dihapus.');
    }
}
