<?php

namespace Tests\Feature;

use App\Models\PpdbJalur;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbDocumentsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
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

    public function test_registration_with_documents_stores_files(): void
    {
        Storage::fake('public');
        $wave = $this->openWave();

        $response = $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), [
            ...$this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]),
            'kk_file' => UploadedFile::fake()->create('kk.pdf', 200, 'application/pdf'),
            'akta_file' => UploadedFile::fake()->image('akta.jpg'),
            'rapor_file' => UploadedFile::fake()->create('rapor.pdf', 200, 'application/pdf'),
            'photo' => UploadedFile::fake()->image('foto.jpg'),
        ]);

        $response->assertRedirect(route('ppdb.index'));

        $registration = PpdbRegistration::firstOrFail();

        foreach (['kk_file', 'akta_file', 'rapor_file', 'photo'] as $field) {
            $this->assertNotNull($registration->{$field});
            Storage::disk('public')->assertExists($registration->{$field});
        }

        $this->assertFalse($registration->kk_verified);
        $this->assertEquals(PpdbRegistration::STATUS_MENUNGGU, $registration->status);
    }

    public function test_registration_without_documents_still_works(): void
    {
        $wave = $this->openWave();

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), $this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]))
            ->assertRedirect(route('ppdb.index'));

        $registration = PpdbRegistration::firstOrFail();
        $this->assertNull($registration->kk_file);
        $this->assertNull($registration->photo);
    }

    public function test_registration_rejects_invalid_document_type(): void
    {
        Storage::fake('public');
        $wave = $this->openWave();

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), [
            ...$this->ppdbPayload(['ppdb_wave_id' => $wave->id, 'captcha' => 7]),
            'kk_file' => UploadedFile::fake()->create('kk.exe', 200, 'application/octet-stream'),
        ])->assertSessionHasErrors('kk_file');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_admin_can_verify_documents_and_set_cadangan(): void
    {
        $registration = PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Siswa Berkas']),
            'registration_number' => 'PPDB-2026-0021',
            'kk_file' => 'ppdb-documents/kk.pdf',
            'status' => PpdbRegistration::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin())->patch(route('admin.ppdb.update', $registration), [
            'status' => PpdbRegistration::STATUS_CADANGAN,
            'kk_verified' => '1',
            'verification_note' => 'KK sudah cocok, bawa aslinya saat daftar ulang.',
        ])->assertRedirect(route('admin.ppdb.show', $registration));

        $registration->refresh();

        $this->assertEquals(PpdbRegistration::STATUS_CADANGAN, $registration->status);
        $this->assertTrue($registration->kk_verified);
        $this->assertFalse($registration->akta_verified);
        $this->assertEquals('KK sudah cocok, bawa aslinya saat daftar ulang.', $registration->verification_note);
    }

    public function test_check_page_shows_cadangan_and_document_status(): void
    {
        PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Siswa Cadangan']),
            'registration_number' => 'PPDB-2026-0022',
            'status' => PpdbRegistration::STATUS_CADANGAN,
            'kk_file' => 'ppdb-documents/kk.pdf',
            'kk_verified' => true,
            'verification_note' => 'Menunggu kuota tambahan.',
        ]);

        $this->get(route('ppdb.check', ['nomor' => 'PPDB-2026-0022']))
            ->assertOk()
            ->assertSee('Cadangan')
            ->assertSee('Terverifikasi')
            ->assertSee('Menunggu kuota tambahan.');
    }

    public function test_deleting_registration_removes_uploaded_files(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('kk.pdf', 100, 'application/pdf')->store('ppdb-documents', 'public');

        $registration = PpdbRegistration::create([
            ...$this->ppdbPayload(['student_name' => 'Hapus Berkas']),
            'registration_number' => 'PPDB-2026-0023',
            'kk_file' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $this->actingAs($this->admin())->delete(route('admin.ppdb.destroy', $registration))
            ->assertRedirect(route('admin.ppdb.index'));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('ppdb_registrations', ['registration_number' => 'PPDB-2026-0023']);
    }
}
