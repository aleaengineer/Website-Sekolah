<?php

namespace Tests\Feature;

use App\Models\AcademicCalendar;
use App\Models\Agenda;
use App\Models\Document;
use App\Models\PpdbJalur;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbWaveAgendaDocumentTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    private function openWave(array $overrides = []): PpdbWave
    {
        return PpdbWave::create([
            'name' => 'Gelombang 1',
            'description' => 'Pendaftaran awal.',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'quota' => 2,
            'is_active' => true,
            ...$overrides,
        ]);
    }

    private function ppdbPayload(array $overrides = []): array
    {
        PpdbJalur::firstOrCreate(
            ['slug' => 'zonasi'],
            ['name' => 'Zonasi', 'is_active' => true, 'sort_order' => 1]
        );

        return [
            'student_name' => 'Budi Santoso',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-05-10',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Kalijati',
            'parent_name' => 'Ahmad Santoso',
            'parent_phone' => '081234567890',
            'address' => 'Dusun Kalijati, Sidamulih',
            'jalur' => 'zonasi',
            ...$overrides,
        ];
    }

    public function test_ppdb_registration_requires_open_wave_with_quota(): void
    {
        $wave = $this->openWave();

        $response = $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), $this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]));

        $response->assertRedirect(route('ppdb.index'));
        $this->assertDatabaseHas('ppdb_registrations', [
            'student_name' => 'Budi Santoso',
            'ppdb_wave_id' => $wave->id,
        ]);
    }

    public function test_ppdb_registration_rejects_closed_wave(): void
    {
        $wave = $this->openWave([
            'name' => 'Gelombang Tutup',
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->subWeek()->toDateString(),
        ]);

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), $this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]))
            ->assertSessionHasErrors('ppdb_wave_id');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_ppdb_registration_rejects_full_wave(): void
    {
        $wave = $this->openWave(['quota' => 1]);

        PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Pendaftar Pertama']),
            'registration_number' => 'PPDB-2026-0001',
            'ppdb_wave_id' => $wave->id,
        ]);

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), $this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]))
            ->assertSessionHasErrors('ppdb_wave_id');

        $this->assertDatabaseCount('ppdb_registrations', 1);
    }

    public function test_ppdb_page_lists_waves_with_remaining_quota(): void
    {
        $this->openWave(['name' => 'Gelombang A']);

        $this->get(route('ppdb.index'))
            ->assertOk()
            ->assertSee('Gelombang A')
            ->assertSee('Sisa kuota 2 dari 2');
    }

    public function test_admin_can_manage_ppdb_waves(): void
    {
        $this->actingAs($this->admin())->post(route('admin.waves.store'), [
            'name' => 'Gelombang 2',
            'description' => 'Susulan.',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'quota' => 30,
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('ppdb_waves', ['name' => 'Gelombang 2', 'quota' => 30]);
    }

    public function test_admin_can_filter_ppdb_by_wave(): void
    {
        $waveA = $this->openWave(['name' => 'Gelombang A']);
        $waveB = $this->openWave(['name' => 'Gelombang B']);

        PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Siswa A']),
            'registration_number' => 'PPDB-2026-0011',
            'ppdb_wave_id' => $waveA->id,
        ]);
        PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Siswa B']),
            'registration_number' => 'PPDB-2026-0012',
            'ppdb_wave_id' => $waveB->id,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.ppdb.index', ['ppdb_wave_id' => $waveA->id]))
            ->assertOk()
            ->assertSee('Siswa A')
            ->assertDontSee('Siswa B');
    }

    public function test_published_agenda_renders_and_draft_returns_404(): void
    {
        $agenda = Agenda::create([
            'title' => 'MPLS Tahun Ajaran Baru',
            'slug' => 'mpls-tahun-ajaran-baru',
            'description' => 'Pengenalan lingkungan sekolah.',
            'location' => 'Lapangan',
            'start_at' => now()->addDays(3),
            'is_published' => true,
        ]);
        $draft = Agenda::create([
            'title' => 'Draf Rapat Internal',
            'slug' => 'draf-rapat-internal',
            'description' => 'Belum tayang.',
            'start_at' => now()->addDays(3),
            'is_published' => false,
        ]);

        $this->get(route('agendas.index'))
            ->assertOk()
            ->assertSee($agenda->title)
            ->assertDontSee($draft->title);

        $this->get(route('agendas.show', $agenda))->assertOk()->assertSee($agenda->title);
        $this->get(route('agendas.show', $draft))->assertNotFound();
    }

    public function test_academic_page_shows_calendar_and_documents(): void
    {
        AcademicCalendar::create([
            'title' => 'Asesmen Tengah Semester',
            'description' => 'ATS ganjil.',
            'start_date' => now()->addMonth()->toDateString(),
            'category' => 'ujian',
            'is_published' => true,
        ]);
        AcademicCalendar::create([
            'title' => 'Draf Kalender Internal',
            'start_date' => now()->addMonth()->toDateString(),
            'category' => 'kegiatan',
            'is_published' => false,
        ]);

        Storage::fake('public');
        $document = Document::create([
            'title' => 'Tata Tertib Peserta Didik',
            'description' => 'Aturan harian.',
            'file' => UploadedFile::fake()->create('tata-tertib.pdf', 100)->store('documents', 'public'),
            'category' => 'regulasi',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('academic'))
            ->assertOk()
            ->assertSee('Asesmen Tengah Semester')
            ->assertDontSee('Draf Kalender Internal')
            ->assertSee('Tata Tertib Peserta Didik')
            ->assertSee($document->file, false);
    }

    public function test_admin_can_manage_agenda_calendar_and_document(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.agendas.store'), [
            'title' => 'Upacara Hari Pahlawan',
            'description' => 'Upacara peringatan.',
            'location' => 'Lapangan',
            'start_at' => now()->addWeek()->format('Y-m-d\TH:i'),
            'is_published' => '1',
        ])->assertRedirect();
        $this->assertDatabaseHas('agendas', ['title' => 'Upacara Hari Pahlawan']);

        $this->actingAs($admin)->post(route('admin.calendars.store'), [
            'title' => 'Libur Semester',
            'start_date' => now()->addMonths(2)->toDateString(),
            'category' => 'libur',
            'is_published' => '1',
        ])->assertRedirect();
        $this->assertDatabaseHas('academic_calendars', ['title' => 'Libur Semester']);

        Storage::fake('public');
        $this->actingAs($admin)->post(route('admin.documents.store'), [
            'title' => 'Formulir PPDB',
            'category' => 'formulir',
            'sort_order' => 1,
            'file' => UploadedFile::fake()->create('formulir.pdf', 100),
        ])->assertRedirect();
        $this->assertDatabaseHas('documents', ['title' => 'Formulir PPDB']);
    }

    public function test_guru_is_forbidden_from_new_modules(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.agendas.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.calendars.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.documents.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.waves.index'))->assertForbidden();
    }

    public function test_sitemap_includes_agenda_urls(): void
    {
        Agenda::create([
            'title' => 'Class Meeting Akhir Semester',
            'slug' => 'class-meeting-akhir-semester',
            'description' => 'Lomba antarkelas.',
            'start_at' => now()->addDays(10),
            'is_published' => true,
        ]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee(route('agendas.index'), false)
            ->assertSee('class-meeting-akhir-semester', false);
    }
}
