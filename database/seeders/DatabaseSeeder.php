<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@undipa.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Create Members
        $member1 = User::create([
            'name' => 'Willy',
            'email' => 'willy@undipa.ac.id',
            'password' => bcrypt('password'),
            'role' => 'member',
            'nim' => '212044',
            'phone' => '081234567890',
            'faculty' => 'Teknik Informatika',
            'voice_part' => 'Tenor',
            'status_anggota' => 'Aktif',
        ]);

        $member2 = User::create([
            'name' => 'Siti',
            'email' => 'siti@undipa.ac.id',
            'password' => bcrypt('password'),
            'role' => 'member',
            'nim' => '212045',
            'phone' => '081234567891',
            'faculty' => 'Sistem Informasi',
            'voice_part' => 'Soprano',
            'status_anggota' => 'Aktif',
        ]);

        // 3. Create Schedules
        $schedule1 = \App\Models\Schedule::create([
            'title' => 'Latihan Penampilan Puncak',
            'description' => 'Latihan persipan gladi resik untuk penampilan Dies Natalis Universitas Dipa Makassar.',
            'date' => date('Y-m-d', strtotime('+2 days')),
            'time' => '18:00:00',
            'location' => 'Auditorium Utama UNDIPA',
            'status' => 'active',
        ]);

        $schedule2 = \App\Models\Schedule::create([
            'title' => 'Latihan Bagian (Tenor)',
            'description' => 'Ruang Paduan Suara Lt. 2: Fokus pada latihan suara tenor untuk repertoar baru.',
            'date' => date('Y-m-d', strtotime('-5 days')),
            'time' => '16:00:00',
            'location' => 'Ruang Paduan Suara Lt. 2',
            'status' => 'completed',
        ]);

        // 4. Create Attendances
        \App\Models\Attendance::create([
            'user_id' => $member1->id,
            'schedule_id' => $schedule2->id,
            'status' => 'Hadir',
        ]);
        
        \App\Models\Attendance::create([
            'user_id' => $member2->id,
            'schedule_id' => $schedule2->id,
            'status' => 'Izin',
            'notes' => 'Sakit demam',
        ]);
    }
}
