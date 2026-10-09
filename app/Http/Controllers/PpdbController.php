<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpdbRegistrationRequest;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        $waves = PpdbWave::active()->withCount('registrations')->get();

        return view('pages.ppdb', [
            'jalurList' => [
                'zonasi' => 'Zonasi',
                'afirmasi' => 'Afirmasi',
                'prestasi' => 'Prestasi',
                'mutasi' => 'Perpindahan Tugas Orang Tua',
            ],
            'waves' => $waves,
            'openWaves' => $waves->filter(fn (PpdbWave $wave) => $wave->isOpen() && ! $wave->isFull()),
        ]);
    }

    public function store(StorePpdbRegistrationRequest $request): RedirectResponse
    {
        $wave = PpdbWave::findOrFail($request->validated('ppdb_wave_id'));

        if (! $wave->isOpen()) {
            return redirect()
                ->route('ppdb.index')
                ->withErrors(['ppdb_wave_id' => 'Gelombang yang dipilih sedang tidak dibuka.'])
                ->withInput();
        }

        if ($wave->isFull()) {
            return redirect()
                ->route('ppdb.index')
                ->withErrors(['ppdb_wave_id' => "Kuota {$wave->name} sudah penuh."])
                ->withInput();
        }

        $registration = PpdbRegistration::create([
            ...$request->safe()->only([
                'student_name',
                'birth_place',
                'birth_date',
                'gender',
                'previous_school',
                'parent_name',
                'parent_phone',
                'address',
                'jalur',
                'ppdb_wave_id',
            ]),
            ...$this->uploadedFiles($request),
            'registration_number' => PpdbRegistration::generateNumber(),
        ]);

        return redirect()
            ->route('ppdb.index')
            ->with('success', "Pendaftaran berhasil! Nomor pendaftaran Anda: {$registration->registration_number}. Simpan nomor ini untuk verifikasi berkas ke sekolah.");
    }

    public function check(Request $request): View|RedirectResponse
    {
        $number = trim((string) $request->query('nomor', ''));
        $registration = null;

        if ($number !== '') {
            $registration = PpdbRegistration::with('wave')->where('registration_number', $number)->first();

            if (! $registration) {
                return redirect()
                    ->route('ppdb.check')
                    ->withErrors(['nomor' => 'Nomor pendaftaran tidak ditemukan. Periksa kembali nomor Anda.'])
                    ->withInput();
            }
        }

        return view('pages.ppdb-check', ['registration' => $registration]);
    }

    public function print(string $registrationNumber): View
    {
        return view('pages.ppdb-print', [
            'registration' => PpdbRegistration::with('wave')->where('registration_number', $registrationNumber)->firstOrFail(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function uploadedFiles(StorePpdbRegistrationRequest $request): array
    {
        $files = [];

        foreach (['kk_file', 'akta_file', 'rapor_file', 'photo'] as $field) {
            if ($request->hasFile($field)) {
                $files[$field] = $request->file($field)->store('ppdb-documents', 'public');
            }
        }

        return $files;
    }
}
