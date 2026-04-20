<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Attendance;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $schedules = Schedule::orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        // default to latest schedule
        $selectedScheduleId = $request->query('schedule_id');
        $selectedSchedule = null;

        if ($selectedScheduleId) {
            $selectedSchedule = Schedule::find($selectedScheduleId);
        } else {
            $selectedSchedule = $schedules->first();
        }

        $members = User::where('role', 'member')->get();
        
        $attendances = collect();
        if ($selectedSchedule) {
            $attendances = Attendance::where('schedule_id', $selectedSchedule->id)->get()->keyBy('user_id');
        }

        $stats = [
            'expected' => collect($members)->count(),
            'present' => $attendances->whereIn('status', ['Hadir', 'Terlambat'])->count(),
            'excused' => $attendances->whereIn('status', ['Izin', 'Sakit'])->count(),
            'alpha' => collect($members)->count() - $attendances->whereIn('status', ['Hadir', 'Terlambat', 'Izin', 'Sakit'])->count(),
        ];

        return view('admin.attendance.index', compact('schedules', 'selectedSchedule', 'members', 'attendances', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha,Belum Absen',
        ]);

        if ($request->status === 'Belum Absen') {
            Attendance::where('schedule_id', $request->schedule_id)
                ->where('user_id', $request->user_id)
                ->delete();
        } else {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $request->schedule_id,
                    'user_id' => $request->user_id,
                ],
                [
                    'status' => $request->status,
                ]
            );
        }

        return redirect()->route('admin.attendance.index', ['schedule_id' => $request->schedule_id])->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function exportCsv()
    {
        $fileName = 'laporan_absensi_padus_' . date('Y-m-d_H-i') . '.csv';
        
        $attendances = Attendance::with(['schedule', 'user'])
            ->join('schedules', 'attendances.schedule_id', '=', 'schedules.id')
            ->orderBy('schedules.date', 'desc')
            ->orderBy('schedules.time', 'desc')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Tanggal', 'Jam', 'Nama Kegiatan', 'Nama Anggota', 'STB/NIM', 'Jenis Suara', 'Status', 'Catatan Waktu'];

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $att) {
                $row = [
                    $att->schedule->date,
                    $att->schedule->time,
                    $att->schedule->name,
                    $att->user->name,
                    $att->user->nim ?? '-',
                    $att->user->voice_part ?? '-',
                    $att->status,
                    $att->created_at->format('H:i:s')
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
