<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpdbRegistrationRequest;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        return view('pages.ppdb', [
            'jalurList' => [
                'zonasi' => 'Zonasi',
                'afirmasi' => 'Afirmasi',
                'prestasi' => 'Prestasi',
                'mutasi' => 'Perpindahan Tugas Orang Tua',
            ],
        ]);
    }

    public function store(StorePpdbRegistrationRequest $request): RedirectResponse
    {
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
            ]),
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
            $registration = PpdbRegistration::where('registration_number', $number)->first();

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
            'registration' => PpdbRegistration::where('registration_number', $registrationNumber)->firstOrFail(),
        ]);
    }
}
