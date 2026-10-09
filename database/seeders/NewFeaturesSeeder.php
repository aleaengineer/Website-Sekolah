<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\Agenda;
use App\Models\PpdbJalur;
use App\Models\PpdbWave;
use Illuminate\Database\Seeder;

class NewFeaturesSeeder extends Seeder
{
    /**
     * Seed waves, agendas, and calendars without duplicating teachers/news.
     */
    public function run(): void
    {
        $year = (int) now()->year;

        foreach ([
            [
                'name' => "Gelombang 1 {$year}",
                'description' => 'Pendaftaran awal untuk semua jalur. Segera verifikasi berkas ke sekolah setelah mendaftar online.',
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'quota' => 64,
                'is_active' => true,
            ],
            [
                'name' => "Gelombang 2 {$year}",
                'description' => 'Pendaftaran susulan bila kuota Gelombang 1 belum terpenuhi.',
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addMonths(2)->toDateString(),
                'quota' => 32,
                'is_active' => true,
            ],
        ] as $wave) {
            PpdbWave::updateOrCreate(['name' => $wave['name']], $wave);
        }

        foreach (PpdbJalur::defaults() as $jalur) {
            PpdbJalur::updateOrCreate(
                ['slug' => $jalur['slug']],
                [...$jalur, 'is_active' => true]
            );
        }

        Agenda::updateOrCreate(['slug' => 'mpls'], [
            'title' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)',
            'description' => 'Kegiatan pengenalan lingkungan sekolah bagi peserta didik baru: tata tertib, sarana prasarana, dan ekstrakurikuler.',
            'location' => 'Lapangan dan Ruang Kelas',
            'start_at' => now()->addDays(14)->setTime(7, 0),
            'end_at' => now()->addDays(16)->setTime(12, 0),
            'is_published' => true,
        ]);

        AcademicCalendar::updateOrCreate(
            ['title' => 'Awal Semester Ganjil', 'start_date' => now()->toDateString()],
            [
                'description' => 'Hari pertama pembelajaran efektif semester ganjil.',
                'category' => 'kegiatan',
                'is_published' => true,
            ]
        );
    }
}
