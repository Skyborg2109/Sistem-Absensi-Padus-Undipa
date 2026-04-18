<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\MemberAnnouncementController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    
    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Download Announcement Attachment (Shared)
    Route::get('/announcements/{announcement}/download', [MemberAnnouncementController::class, 'download'])->name('announcements.download');

    // Notifications Route
    Route::get('/notifications/unread', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
            'notifications' => auth()->user()->unreadNotifications()->take(5)->get()
        ]);
    })->name('notifications.unread');
    
    // Shared Routes
    Route::get('/profile', function () {
        return view('profile.index', [
            'user' => auth()->user()
        ]);
    })->name('profile');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'total_members' => \App\Models\User::where('role', 'member')->count(),
                'active_schedules' => \App\Models\Schedule::where('status', 'active')->count()
            ]);
        })->name('dashboard');

        Route::get('/members', [\App\Http\Controllers\AdminMemberController::class, 'index'])->name('members.index');
        Route::post('/members', [\App\Http\Controllers\AdminMemberController::class, 'store'])->name('members.store');
        Route::put('/members/{member}', [\App\Http\Controllers\AdminMemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [\App\Http\Controllers\AdminMemberController::class, 'destroy'])->name('members.destroy');

        Route::get('/attendance', [\App\Http\Controllers\AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [\App\Http\Controllers\AdminAttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/export', [\App\Http\Controllers\AdminAttendanceController::class, 'exportCsv'])->name('attendance.export');

        Route::get('/schedules', [\App\Http\Controllers\AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [\App\Http\Controllers\AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::put('/schedules/{schedule}', [\App\Http\Controllers\AdminScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [\App\Http\Controllers\AdminScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Example routes for future post/put actions
        // Route::post('/schedules', [AdminController::class, 'storeSchedule']);

        // Announcements
        Route::resource('/announcements', \App\Http\Controllers\AdminAnnouncementController::class)->names('announcements');
    });

    // Member Routes
    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', function () {
            $nextSchedule = \App\Models\Schedule::where('status', 'active')->orderBy('date')->first();
            $latestAnnouncements = \App\Models\Announcement::where('is_active', true)->latest()->take(2)->get();
            return view('member.dashboard', [
                'user' => auth()->user(),
                'nextSchedule' => $nextSchedule,
                'recentAttendances' => auth()->user()->attendances()->with('schedule')->take(3)->get(),
                'latestAnnouncements' => $latestAnnouncements
            ]);
        })->name('dashboard');

        Route::get('/attendance/history', function () {
            return view('member.attendance.history', [
                'attendances' => auth()->user()->attendances()->with('schedule')->orderByDesc('created_at')->get()
            ]);
        })->name('attendance.history');

        Route::get('/attendance/check-in', function () {
            $activeSchedule = \App\Models\Schedule::where('status', 'active')->first();
            $alreadyCheckedIn = false;
            $isCheckinAllowed = false;
            $checkinStatusMsg = '';
            $attendanceRecord = null;
            
            if ($activeSchedule) {
                $attendanceRecord = \App\Models\Attendance::where('user_id', auth()->id())
                    ->where('schedule_id', $activeSchedule->id)
                    ->first();
                $alreadyCheckedIn = $attendanceRecord ? true : false;
                    
                $now = \Carbon\Carbon::now();
                $scheduleDate = \Carbon\Carbon::parse($activeSchedule->date)->startOfDay();
                $startTime = \Carbon\Carbon::parse($activeSchedule->date . ' ' . $activeSchedule->time);
                $endTime = $activeSchedule->end_time ? \Carbon\Carbon::parse($activeSchedule->date . ' ' . $activeSchedule->end_time) : null;
                
                if ($now->isBefore($scheduleDate)) {
                    $checkinStatusMsg = 'Presensi belum dibuka. Jadwal latihan pada ' . $scheduleDate->format('d M Y') . ' jam ' . $startTime->format('H:i') . ' WITA.';
                } elseif ($now->isAfter($scheduleDate->copy()->endOfDay())) {
                    $checkinStatusMsg = 'Waktu presensi sudah berlalu.';
                } elseif ($now->isBefore($startTime)) {
                    $checkinStatusMsg = 'Presensi belum dibuka. Kembali pada jam ' . $startTime->format('H:i') . ' WITA.';
                } elseif ($endTime && $now->isAfter($endTime)) {
                    $checkinStatusMsg = 'Waktu presensi selesai pada ' . $endTime->format('H:i') . ' WITA.';
                } else {
                    $isCheckinAllowed = true;
                }
            }

            return view('member.attendance.check_in', [
                'schedule' => $activeSchedule,
                'alreadyCheckedIn' => $alreadyCheckedIn,
                'attendanceRecord' => $attendanceRecord,
                'isCheckinAllowed' => $isCheckinAllowed,
                'checkinStatusMsg' => $checkinStatusMsg
            ]);
        })->name('attendance.checkin');
        
        Route::post('/attendance/check-in', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'required|in:Hadir,Izin,Sakit',
                'notes' => 'nullable|string|max:255'
            ]);
            
            $schedule = \App\Models\Schedule::findOrFail($request->schedule_id);
            if ($schedule->status !== 'active') {
                return redirect()->back()->withErrors(['message' => 'Sesi latihan sudah tidak aktif. Anda tidak dapat melakukan check-in.']);
            }
            
            $now = \Carbon\Carbon::now();
            $startTime = \Carbon\Carbon::parse($schedule->date . ' ' . $schedule->time);
            $endTime = $schedule->end_time ? \Carbon\Carbon::parse($schedule->date . ' ' . $schedule->end_time) : null;
            
            if ($now->isBefore($startTime)) {
                return redirect()->back()->withErrors(['message' => 'Belum waktunya absen.']);
            }
            if ($endTime && $now->isAfter($endTime)) {
                return redirect()->back()->withErrors(['message' => 'Waktu absen sudah terlewat.']);
            }
            
            $status = $request->status;
            // Jika status Hadir, periksa keterlambatan (> 10 menit)
            if ($status === 'Hadir') {
                $lateThreshold = $startTime->copy()->addMinutes(10);
                if ($now->isAfter($lateThreshold)) {
                    $status = 'Terlambat';
                }
            }
            
            \App\Models\Attendance::updateOrCreate(
                ['user_id' => auth()->id(), 'schedule_id' => $request->schedule_id],
                ['status' => $status, 'notes' => $request->notes]
            );
            
            return redirect()->back()->with('success', 'Presensi berhasil disimpan!');
        })->name('attendance.store');
        
        // Announcements
        Route::get('/announcements', [\App\Http\Controllers\MemberAnnouncementController::class, 'index'])->name('announcements.index');
    });
});
