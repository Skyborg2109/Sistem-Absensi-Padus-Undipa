<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class MemberAnnouncementController extends Controller
{
    public function index()
    {
        // Get all active announcements/rules
        $announcements = Announcement::where('is_active', true)->latest()->get();
        return view('member.announcements.index', compact('announcements'));
    }

    public function download(Announcement $announcement)
    {
        if (!$announcement->attachment_path) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $announcement->attachment_path), $announcement->attachment_name);
    }
}
