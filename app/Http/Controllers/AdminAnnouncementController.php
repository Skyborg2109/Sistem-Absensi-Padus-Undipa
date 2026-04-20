<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pengumuman,peraturan',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // max 10MB
        ]);

        $data = $request->only(['title', 'content', 'type']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_name'] = $file->getClientOriginalName();
            $data['attachment_path'] = $file->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman / Aturan berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pengumuman,peraturan',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = $request->only(['title', 'content', 'type']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('attachment')) {
            // Delete old file
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            $file = $request->file('attachment');
            $data['attachment_name'] = $file->getClientOriginalName();
            $data['attachment_path'] = $file->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman / Aturan berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Data berhasil dihapus.');
    }

    public function deleteAttachment(Announcement $announcement)
    {
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
            $announcement->update([
                'attachment_path' => null,
                'attachment_name' => null
            ]);
            return redirect()->back()->with('success', 'File terpasang berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Tidak ada file untuk dihapus.');
    }
}
