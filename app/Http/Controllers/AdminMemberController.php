<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('voice')) {
            // Because voice can be "Soprano", we might want to match "Soprano 1", "Soprano 2"
            $voice = $request->voice;
            if ($voice !== 'Semua Suara') {
                $query->where('voice_part', 'like', "{$voice}%");
            }
        }

        $members = $query->get();
        return view('admin.members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'required|string|max:20|unique:users',
            'voice_part' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'member';

        User::create($validated);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'nim' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($member->id)],
            'voice_part' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
    }

    public function exportCsv()
    {
        $fileName = 'direktori_anggota_padus_' . date('Y-m-d') . '.csv';
        $members = User::where('role', 'member')->orderBy('name')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Nama Lengkap', 'STB/NIM', 'Email', 'Jenis Suara', 'Nomor Telepon'];

        $callback = function() use($members, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($members as $member) {
                $row = [
                    $member->name,
                    $member->nim,
                    $member->email,
                    $member->voice_part ?? '-',
                    $member->phone ?? '-'
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
