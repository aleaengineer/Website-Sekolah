<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
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
}
