<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\Agenda;
use App\Models\Category;
use App\Models\Document;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbJalur;
use App\Models\PpdbWave;
use App\Models\Setting;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->settings() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Teacher::factory()
            ->count(8)
            ->sequence(fn ($sequence) => ['sort_order' => $sequence->index + 1])
            ->create();

        foreach (['Akademik', 'Kegiatan', 'Prestasi', 'PPDB'] as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        News::factory()->count(6)->create([
            'category_id' => fn () => Category::query()->inRandomOrder()->first()->id,
        ]);

        Gallery::factory()
            ->count(8)
            ->sequence(fn ($sequence) => ['sort_order' => $sequence->index + 1])
            ->create();

        foreach ($this->extracurriculars() as $index => $extracurricular) {
            Extracurricular::updateOrCreate(
                ['slug' => $extracurricular['slug']],
                [...$extracurricular, 'sort_order' => $index + 1]
            );
        }

        foreach ($this->ppdbWaves() as $wave) {
            PpdbWave::updateOrCreate(
                ['name' => $wave['name']],
                $wave
            );
        }

        foreach ($this->agendas() as $agenda) {
            Agenda::updateOrCreate(
                ['slug' => $agenda['slug']],
                $agenda
            );
        }

        foreach ($this->calendars() as $calendar) {
            AcademicCalendar::updateOrCreate(
                ['title' => $calendar['title'], 'start_date' => $calendar['start_date']],
                $calendar
            );
        }

        foreach ($this->documents() as $index => $document) {
            Document::updateOrCreate(
                ['title' => $document['title']],
                [...$document, 'sort_order' => $index + 1]
            );
        }

        foreach (PpdbJalur::defaults() as $jalur) {
            PpdbJalur::updateOrCreate(
                ['slug' => $jalur['slug']],
                [...$jalur, 'is_active' => true]
            );
        }
    }

    /**
     * School identity, contact, and content placeholders.
     * Values marked "segera dilengkapi" are meant to be updated gradually.
     *
     * @return array<string, string>
     */
    private function settings(): array
    {
        return [
            'school.logo' => '',
            'school.name' => 'SMP Negeri Satu Atap I Sidamulih',
            'school.short_name' => 'SMPN Satu Atap I Sidamulih',
            'school.npsn' => '20253310',
            'school.accreditation' => 'B',
            'school.status' => 'Negeri',
            'school.address' => 'Jl. Karanganyar No.127 Kalijati',
            'school.village' => 'Kalijati',
            'school.district' => 'Sidamulih',
            'school.regency' => 'Pangandaran',
            'school.province' => 'Jawa Barat',
            'school.phone' => '08xx-xxxx-xxxx (segera dilengkapi)',
            'school.email' => 'info@satap1sidamulih.sch.id (segera dilengkapi)',
            'school.maps_embed' => 'https://www.google.com/maps?q=Sidamulih,Pangandaran,Jawa+Barat&output=embed',
            'school.principal_name' => '— (segera dilengkapi)',
            'school.principal_greeting' => 'Assalamualaikum warahmatullahi wabarakatuh. Selamat datang di website resmi SMP Negeri Satu Atap I Sidamulih. Website ini kami hadirkan sebagai sarana informasi, komunikasi, dan transparansi layanan pendidikan bagi peserta didik, orang tua, dan masyarakat. (Sambutan resmi segera dilengkapi.)',
            'school.description' => 'SMP Negeri Satu Atap I Sidamulih adalah sekolah menengah pertama negeri di Kecamatan Sidamulih, Kabupaten Pangandaran, yang berkomitmen memberikan layanan pendidikan yang bermutu, berkarakter, dan berdaya saing.',
            'school.vision' => 'Terwujudnya peserta didik yang beriman, cerdas, terampil, mandiri, dan berakhlak mulia. (Visi resmi segera dilengkapi.)',
            'school.mission' => "1. Menyelenggarakan pembelajaran yang aktif, kreatif, dan menyenangkan.\n2. Menumbuhkan keimanan, ketakwaan, dan akhlak mulia.\n3. Mengembangkan bakat dan minat peserta didik melalui kegiatan ekstrakurikuler.\n4. Membangun budaya disiplin, gotong royong, dan peduli lingkungan.\n(Misi resmi segera dilengkapi.)",
            'school.history' => 'SMP Negeri Satu Atap I Sidamulih berdiri sebagai bagian dari program Sekolah Satu Atap untuk memperluas akses pendidikan menengah pertama di wilayah Kecamatan Sidamulih, Kabupaten Pangandaran. (Sejarah lengkap segera dilengkapi.)',
            'school.students_count' => '150',
            'ppdb.is_open' => '1',
            'ppdb.year' => '2026/2027',
            'ppdb.quota' => '96',
            'ppdb.info' => 'Penerimaan Peserta Didik Baru (PPDB) SMP Negeri Satu Atap I Sidamulih Tahun Ajaran 2026/2027 dibuka melalui jalur Zonasi, Afirmasi, Prestasi, dan Perpindahan Tugas Orang Tua. Silakan isi formulir pendaftaran online, kemudian lakukan verifikasi berkas ke sekolah dengan membawa nomor pendaftaran.',
            'social.facebook' => '#',
            'social.instagram' => '#',
            'social.youtube' => '#',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extracurriculars(): array
    {
        $items = [
            ['Pramuka', 'Membentuk kemandirian, kedisiplinan, dan jiwa kepemimpinan melalui kegiatan kepramukaan.', 'Sabtu, 13.00 - 15.00 WIB'],
            ['Paskibra', 'Melatih baris-berbaris, kedisiplinan, dan kesiapan menjadi pasukan pengibar bendera.', 'Jumat, 14.00 - 16.00 WIB'],
            ['Futsal', 'Mengembangkan bakat olahraga, kerja sama tim, dan sportivitas melalui sepak bola mini.', 'Rabu, 14.00 - 16.00 WIB'],
            ['Bola Voli', 'Melatih keterampilan bola voli sekaligus menjaga kebugaran dan kekompakan tim.', 'Kamis, 14.00 - 16.00 WIB'],
            ['Seni Tari', 'Melestarikan seni dan budaya daerah melalui latihan tari tradisional.', 'Selasa, 14.00 - 15.30 WIB'],
            ['PMR', 'Membekali keterampilan pertolongan pertama dan kepedulian sosial melalui Palang Merah Remaja.', 'Senin, 14.00 - 15.30 WIB'],
        ];

        return array_map(fn (array $item) => [
            'name' => $item[0],
            'slug' => Str::slug($item[0]),
            'description' => $item[1],
            'coach_name' => null,
            'schedule' => $item[2],
            'is_active' => true,
        ], $items);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function ppdbWaves(): array
    {
        $year = (int) now()->year;

        return [
            [
                'name' => "Gelombang 1 {$year}",
                'description' => 'Pendaftaran awal untuk semua jalur. Segera verifikasi berkas ke sekolah setelah mendaftar online.',
                'start_date' => "{$year}-05-01",
                'end_date' => "{$year}-06-15",
                'quota' => 64,
                'is_active' => true,
            ],
            [
                'name' => "Gelombang 2 {$year}",
                'description' => 'Pendaftaran susulan bila kuota Gelombang 1 belum terpenuhi.',
                'start_date' => "{$year}-06-16",
                'end_date' => "{$year}-07-15",
                'quota' => 32,
                'is_active' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function agendas(): array
    {
        return [
            [
                'title' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)',
                'slug' => 'mpls',
                'description' => 'Kegiatan pengenalan lingkungan sekolah bagi peserta didik baru: tata tertib, sarana prasarana, dan ekstrakurikuler.',
                'location' => 'Lapangan & Ruang Kelas',
                'start_at' => now()->addDays(14)->setTime(7, 0),
                'end_at' => now()->addDays(16)->setTime(12, 0),
                'cover_image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Upacara Bendera Hari Senin',
                'slug' => 'upacara-bendera-hari-senin',
                'description' => 'Upacara bendera rutin setiap Senin pagi yang wajib diikuti seluruh siswa, guru, dan tendik.',
                'location' => 'Lapangan Upacara',
                'start_at' => now()->addDays(7)->setTime(7, 0),
                'end_at' => null,
                'cover_image' => null,
                'is_published' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function calendars(): array
    {
        $year = (int) now()->year;

        return [
            [
                'title' => 'Awal Semester Ganjil',
                'description' => 'Hari pertama pembelajaran efektif semester ganjil dan MPLS peserta didik baru.',
                'start_date' => "{$year}-07-15",
                'end_date' => null,
                'category' => 'kegiatan',
                'is_published' => true,
            ],
            [
                'title' => 'Asesmen Sumatif Tengah Semester',
                'description' => 'Pelaksanaan asesmen tengah semester ganjil untuk semua kelas.',
                'start_date' => "{$year}-10-06",
                'end_date' => "{$year}-10-11",
                'category' => 'ujian',
                'is_published' => true,
            ],
            [
                'title' => 'Libur Semester Ganjil',
                'description' => 'Libur akhir semester ganjil dan persiapan rapor.',
                'start_date' => "{$year}-12-22",
                'end_date' => ($year + 1).'-01-04',
                'category' => 'libur',
                'is_published' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function documents(): array
    {
        return [
            [
                'title' => 'Formulir Pendaftaran PPDB (Cadangan)',
                'description' => 'Formulir fisik bila pendaftar tidak dapat mengisi formulir online.',
                'file' => 'documents/contoh-formulir-ppdb.pdf',
                'category' => 'formulir',
                'is_published' => false,
            ],
            [
                'title' => 'Tata Tertib Peserta Didik',
                'description' => 'Tata tertib dan pembiasaan harian SMP Negeri Satu Atap I Sidamulih.',
                'file' => 'documents/contoh-tata-tertib.pdf',
                'category' => 'regulasi',
                'is_published' => false,
            ],
        ];
    }
}
