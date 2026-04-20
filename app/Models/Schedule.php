<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'date', 'time', 'end_time', 'location', 'status'])]

class Schedule extends Model
{
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Cek apakah sesi presensi sudah berakhir (melewati batas waktu presensi).
     * Jika ada end_time, cek apakah sekarang sudah lewat end_time di tanggal jadwal.
     * Jika tidak ada end_time, cek apakah tanggal jadwal sudah lewat (hari ini sudah berakhir).
     */
    public function isExpired(): bool
    {
        $now = Carbon::now();

        if ($this->end_time) {
            $deadline = Carbon::parse($this->date . ' ' . $this->end_time);
            return $now->isAfter($deadline);
        }

        // Jika tidak ada end_time, sesi dianggap selesai setelah akhir hari jadwal
        $endOfDay = Carbon::parse($this->date)->endOfDay();
        return $now->isAfter($endOfDay);
    }

    /**
     * Mengembalikan status efektif (computed) dari jadwal.
     * Jika status di DB masih 'active' tapi waktu sudah lewat batas presensi,
     * tampilkan sebagai 'completed' secara otomatis.
     */
    public function getEffectiveStatus(): string
    {
        if ($this->status === 'active' && $this->isExpired()) {
            return 'completed';
        }

        return $this->status;
    }
}
