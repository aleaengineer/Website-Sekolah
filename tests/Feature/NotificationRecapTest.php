<?php

namespace Tests\Feature;

use App\Mail\PpdbStatusMail;
use App\Models\Agenda;
use App\Models\Category;
use App\Models\News;
use App\Models\PpdbJalur;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationRecapTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    private function seedTracks(): void
    {
        foreach (PpdbJalur::defaults() as $jalur) {
            PpdbJalur::create([...$jalur, 'is_active' => true]);
        }
    }

    private function openWave(): PpdbWave
    {
        return PpdbWave::create([
            'name' => 'Gelombang 1',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'quota' => 100,
            'is_active' => true,
        ]);
    }

    public function test_status_update_sends_email_when_parent_email_exists(): void
    {
        Mail::fake();

        $registration = PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0031',
            'student_name' => 'Siswa Email',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Bapak Email',
            'parent_phone' => '081234567890',
            'parent_email' => 'ortu@example.com',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
            'status' => PpdbRegistration::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin())->patch(route('admin.ppdb.update', $registration), [
            'status' => PpdbRegistration::STATUS_DITERIMA,
        ])->assertRedirect();

        Mail::assertSent(PpdbStatusMail::class, fn (PpdbStatusMail $mail) => $mail->hasTo('ortu@example.com'));
    }

    public function test_status_update_skips_email_when_absent(): void
    {
        Mail::fake();

        $registration = PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0032',
            'student_name' => 'Siswa Tanpa Email',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'P',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Ibu Tanpa Email',
            'parent_phone' => '081234567891',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
            'status' => PpdbRegistration::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin())->patch(route('admin.ppdb.update', $registration), [
            'status' => PpdbRegistration::STATUS_DIVERIFIKASI,
        ])->assertRedirect();

        Mail::assertNotSent(PpdbStatusMail::class);
    }

    public function test_ppdb_form_rejects_unknown_track(): void
    {
        $this->seedTracks();
        $wave = $this->openWave();

        $payload = [
            'student_name' => 'Budi Santoso',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-05-10',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Kalijati',
            'parent_name' => 'Ahmad Santoso',
            'parent_phone' => '081234567890',
            'address' => 'Dusun Kalijati',
            'jalur' => 'jalur-khayalan',
            'ppdb_wave_id' => $wave->id,
            'captcha' => 7,
        ];

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), $payload)
            ->assertSessionHasErrors('jalur');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_ppdb_page_lists_dynamic_tracks(): void
    {
        $this->seedTracks();

        $this->get(route('ppdb.index'))
            ->assertOk()
            ->assertSee('Zonasi')
            ->assertSee('Perpindahan Tugas Orang Tua');
    }

    public function test_admin_can_manage_tracks(): void
    {
        $this->actingAs($this->admin())->post(route('admin.jalurs.store'), [
            'name' => 'Prestasi',
            'description' => 'Berdasarkan prestasi.',
            'sort_order' => 3,
            'is_active' => '1',
        ])->assertRedirect();

        $jalur = PpdbJalur::where('slug', 'prestasi')->firstOrFail();

        $this->actingAs($this->admin())->put(route('admin.jalurs.update', $jalur), [
            'name' => 'Prestasi Akademik',
            'sort_order' => 3,
        ])->assertRedirect();

        $this->assertDatabaseHas('ppdb_jalurs', ['id' => $jalur->id, 'name' => 'Prestasi Akademik']);
    }

    public function test_track_with_registrations_cannot_be_deleted(): void
    {
        $this->seedTracks();
        $jalur = PpdbJalur::where('slug', 'zonasi')->firstOrFail();

        PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0033',
            'student_name' => 'Siswa Zonasi',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Bapak Zonasi',
            'parent_phone' => '081234567892',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
        ]);

        $this->actingAs($this->admin())->delete(route('admin.jalurs.destroy', $jalur))
            ->assertSessionHasErrors('jalur');

        $this->assertDatabaseHas('ppdb_jalurs', ['id' => $jalur->id]);
    }

    public function test_dashboard_shows_ppdb_recap(): void
    {
        $this->seedTracks();
        $wave = $this->openWave();

        PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0034',
            'student_name' => 'Rekap Satu',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Bapak Rekap',
            'parent_phone' => '081234567893',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
            'ppdb_wave_id' => $wave->id,
            'status' => PpdbRegistration::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin())->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rekap PPDB')
            ->assertSee('Gelombang 1')
            ->assertSee('Zonasi');
    }

    public function test_print_page_contains_qr_code(): void
    {
        PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0035',
            'student_name' => 'Siswa QR',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Bapak QR',
            'parent_phone' => '081234567894',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
        ]);

        $this->get(route('ppdb.print', 'PPDB-2026-0035'))
            ->assertOk()
            ->assertSee('<svg', false)
            ->assertSee('Pindai untuk cek status');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create(['name' => 'Prestasi', 'slug' => 'prestasi']);

        $this->actingAs($this->admin())->put(route('admin.categories.update', $category), [
            'name' => 'Prestasi Siswa',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Prestasi Siswa']);
    }

    public function test_search_finds_published_content(): void
    {
        News::factory()->create([
            'title' => 'Juara Futsal Sekolah Kebanggaan',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        News::factory()->create([
            'title' => 'Draf Rapat Internal Sekolah',
            'is_published' => false,
            'published_at' => null,
        ]);
        Agenda::create([
            'title' => 'Latihan Futsal Sore Hari',
            'slug' => 'latihan-futsal-sore',
            'description' => 'Latihan rutin tim futsal.',
            'start_at' => now()->addDays(2),
            'is_published' => true,
        ]);

        $this->get(route('search', ['q' => 'Futsal']))
            ->assertOk()
            ->assertSee('Juara Futsal Sekolah Kebanggaan')
            ->assertSee('Latihan Futsal Sore Hari')
            ->assertDontSee('Draf Rapat Internal Sekolah');
    }

    public function test_search_rejects_short_query(): void
    {
        $this->get(route('search', ['q' => 'a']))->assertSessionHasErrors('q');
        $this->get(route('search'))->assertSessionHasErrors('q');
    }

    public function test_guru_is_forbidden_from_tracks(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.jalurs.index'))->assertForbidden();
    }
}
