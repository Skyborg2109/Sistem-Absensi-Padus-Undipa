<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\MemberAnnouncementController;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('member.dashboard');
    }
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

    // Notifications Routes
    Route::get('/notifications/unread', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
            'notifications' => auth()->user()->unreadNotifications()->take(5)->get()
        ]);
    })->name('notifications.unread');

    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.mark-as-read');

    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');
    
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

        Route::get('/members/export', [\App\Http\Controllers\AdminMemberController::class, 'exportCsv'])->name('members.export');
        Route::delete('/announcements/{announcement}/attachment', [\App\Http\Controllers\AdminAnnouncementController::class, 'deleteAttachment'])->name('announcements.deleteAttachment');

        
        // Example routes for future post/put actions
        // Route::post('/schedules', [AdminController::class, 'storeSchedule']);

        // Announcements
        Route::resource('/announcements', \App\Http\Controllers\AdminAnnouncementController::class)->names('announcements');
    });

    // Member Routes
    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', function () {
            // Auto-expire jadwal aktif yang sudah lewat batas presensi
            \App\Models\Schedule::where('status', 'active')->each(function ($s) {
                if ($s->isExpired()) $s->update(['status' => 'completed']);
            });

            $user = auth()->user();
            $nextSchedule = \App\Models\Schedule::where('status', 'active')->orderBy('date')->first();
            $latestAnnouncements = \App\Models\Announcement::where('is_active', true)->latest()->take(2)->get();
            
            // Statistics for Dashboard
            $allAttendances = $user->attendances;
            $hadirCount = $allAttendances->whereIn('status', ['Hadir', 'Terlambat'])->count();
            $totalSesi = $allAttendances->count();
            $attendanceRate = $totalSesi > 0 ? round(($hadirCount / $totalSesi) * 100) : 0;

            return view('member.dashboard', [
                'user' => $user,
                'nextSchedule' => $nextSchedule,
                'recentAttendances' => $user->attendances()->with('schedule')->take(3)->get(),
                'latestAnnouncements' => $latestAnnouncements,
                'stats' => [
                    'hadir_count' => $hadirCount,
                    'total_sesi' => $totalSesi,
                    'percentage' => $attendanceRate
                ]
            ]);
        })->name('dashboard');

        Route::get('/attendance/history', function () {
            return view('member.attendance.history', [
                'attendances' => auth()->user()->attendances()->with('schedule')->orderByDesc('created_at')->get()
            ]);
        })->name('attendance.history');

        Route::get('/attendance/check-in', function () {
            // Cari jadwal hari ini untuk member
            $todaySchedule = \App\Models\Schedule::whereDate('date', \Carbon\Carbon::today())->first();
            
            // Jika tidak ada jadwal hari ini, cari yang statusnya 'active' (mungkin jadwal masa depan yang diaktifkan manual)
            $schedule = $todaySchedule ?? \App\Models\Schedule::where('status', 'active')->orderBy('date')->first();

            $alreadyCheckedIn = false;
            $isCheckinAllowed = false;
            $checkinStatusMsg = '';
            $attendanceRecord = null;
            $sessionExpired = false;
            
            if ($schedule) {
                // Update status ke completed jika expired
                if ($schedule->status === 'active' && $schedule->isExpired()) {
                    $schedule->update(['status' => 'completed']);
                }

                $attendanceRecord = \App\Models\Attendance::where('user_id', auth()->id())
                    ->where('schedule_id', $schedule->id)
                    ->first();
                $alreadyCheckedIn = $attendanceRecord ? true : false;
                    
                $now = \Carbon\Carbon::now();
                $scheduleDate = \Carbon\Carbon::parse($schedule->date)->startOfDay();
                $startTime = \Carbon\Carbon::parse($schedule->date . ' ' . $schedule->time);
                $endTime = $schedule->end_time
                    ? \Carbon\Carbon::parse($schedule->date . ' ' . $schedule->end_time)
                    : null;
                
                if ($now->isBefore($scheduleDate)) {
                    $checkinStatusMsg = 'Presensi belum dibuka. Jadwal latihan pada ' . $scheduleDate->format('d M Y') . ' jam ' . $startTime->format('H:i') . ' WITA.';
                } elseif ($endTime && $now->isAfter($endTime)) {
                    $sessionExpired = true;
                    $checkinStatusMsg = 'Sesi presensi telah ditutup pada ' . $endTime->format('H:i') . ' WITA. Hubungi admin jika ada kendala.';
                } elseif ($now->isAfter($scheduleDate->copy()->endOfDay())) {
                    $sessionExpired = true;
                    $checkinStatusMsg = 'Sesi latihan pada ' . $scheduleDate->format('d M Y') . ' telah selesai.';
                } elseif ($now->isBefore($startTime)) {
                    $checkinStatusMsg = 'Presensi belum dibuka. Kembali pada jam ' . $startTime->format('H:i') . ' WITA.';
                } elseif ($schedule->status === 'completed') {
                    $sessionExpired = true;
                    $checkinStatusMsg = 'Sesi latihan ini telah ditandai selesai oleh admin.';
                } else {
                    $isCheckinAllowed = true;
                }
            }

            return view('member.attendance.check_in', [
                'schedule' => $schedule,
                'alreadyCheckedIn' => $alreadyCheckedIn,
                'attendanceRecord' => $attendanceRecord,
                'isCheckinAllowed' => $isCheckinAllowed,
                'checkinStatusMsg' => $checkinStatusMsg,
                'sessionExpired' => $sessionExpired,
            ]);
        })->name('attendance.checkin');
        
        Route::post('/attendance/check-in', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'required|in:Hadir,Izin,Sakit',
                'notes' => 'nullable|string|max:255',
                'image' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric'
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
            
            // Geofencing: Check if status is 'Hadir' (or if we want to check 'Terlambat' too)
            if ($status === 'Hadir') {
                // Koordinat Universitas Dipa Makassar (KM 9)
                $campusLat = -5.140357;
                $campusLng = 119.480506;
                $allowedRadius = 200; // 200 meter
                
                // Haversine formula for distance
                $earthRadius = 6371000;
                $latFrom = deg2rad($request->latitude);
                $lonFrom = deg2rad($request->longitude);
                $latTo = deg2rad($campusLat);
                $lonTo = deg2rad($campusLng);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                $distance = $angle * $earthRadius;

                if ($distance > $allowedRadius) {
                    return redirect()->back()->withErrors(['message' => 'Presensi ditolak. Anda berada ' . round($distance) . 'm dari kampus. Presensi "Hadir" wajib dilakukan di area Kampus UNDIPA. Pilih "Izin" jika Anda berhalangan.']);
                }

                // Jika status Hadir & di dalam kampus, periksa keterlambatan (> 10 menit)
                $lateThreshold = $startTime->copy()->addMinutes(10);
                if ($now->isAfter($lateThreshold)) {
                    $status = 'Terlambat';
                }
            }

            // Handle Image Upload (Cloudinary)
            $imagePath = null;
            if ($request->image) {
                try {
                    $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
                    $upload = $cloudinary->uploadApi()->upload($request->image, [
                        'folder' => 'attendances',
                        'public_id' => 'attendance_' . auth()->id() . '_' . time(),
                    ]);
                    $imagePath = $upload['secure_url']; // Save the full URL
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['message' => 'Gagal mengunggah foto ke Cloudinary: ' . $e->getMessage()]);
                }
            }
            
            \App\Models\Attendance::updateOrCreate(
                ['user_id' => auth()->id(), 'schedule_id' => $request->schedule_id],
                [
                    'status' => $status, 
                    'notes' => $request->notes,
                    'image_path' => $imagePath,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'location_name' => $request->location_name
                ]
            );
            
            return redirect()->back()->with('success', 'Presensi berhasil disimpan!');
        })->name('attendance.store');
        
        // Announcements
        Route::get('/announcements', [\App\Http\Controllers\MemberAnnouncementController::class, 'index'])->name('announcements.index');
    });
});
