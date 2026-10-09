<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PpdbRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PpdbRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = PpdbRegistration::STATUSES;

        $registrations = PpdbRegistration::with('wave')->latest()
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->input('ppdb_wave_id'), fn ($query, $waveId) => $query->where('ppdb_wave_id', $waveId))
            ->when($request->input('q'), fn ($query, $q) => $query->where(fn ($sub) => $sub
                ->where('student_name', 'like', "%{$q}%")
                ->orWhere('registration_number', 'like', "%{$q}%")))
            ->paginate(15)
            ->withQueryString();

        return view('admin.ppdb-index', [
            'registrations' => $registrations,
            'statuses' => $statuses,
            'waves' => PpdbWave::orderBy('start_date')->get(),
        ]);
    }

    public function show(PpdbRegistration $ppdb): View
    {
        $ppdb->load('wave');

        return view('admin.ppdb-show', [
            'registration' => $ppdb,
            'statuses' => PpdbRegistration::STATUSES,
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:menunggu,diverifikasi,diterima,ditolak,cadangan'],
            'ppdb_wave_id' => ['nullable', 'integer', 'exists:ppdb_waves,id'],
        ]);

        $status = $validated['status'] ?? null;
        $waveId = $validated['ppdb_wave_id'] ?? null;

        $suffix = $status ? "-{$status}" : '';
        $suffix .= $waveId ? "-gelombang{$waveId}" : '';

        return Excel::download(
            new PpdbRegistrationsExport($status, $waveId),
            'ppdb'.now()->format('Y-m-d')."{$suffix}.xlsx"
        );
    }

    public function update(Request $request, PpdbRegistration $ppdb): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,diverifikasi,diterima,ditolak,cadangan'],
            'kk_verified' => ['sometimes', 'boolean'],
            'akta_verified' => ['sometimes', 'boolean'],
            'rapor_verified' => ['sometimes', 'boolean'],
            'photo_verified' => ['sometimes', 'boolean'],
            'verification_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $ppdb->update([
            'status' => $validated['status'],
            'kk_verified' => $request->boolean('kk_verified'),
            'akta_verified' => $request->boolean('akta_verified'),
            'rapor_verified' => $request->boolean('rapor_verified'),
            'photo_verified' => $request->boolean('photo_verified'),
            'verification_note' => $validated['verification_note'] ?? null,
        ]);

        return redirect()
            ->route('admin.ppdb.show', $ppdb)
            ->with('success', "Status pendaftaran {$ppdb->registration_number} diubah menjadi {$validated['status']}.");
    }

    public function destroy(PpdbRegistration $ppdb): RedirectResponse
    {
        foreach (['kk_file', 'akta_file', 'rapor_file', 'photo'] as $field) {
            if ($ppdb->{$field}) {
                Storage::disk('public')->delete($ppdb->{$field});
            }
        }

        $ppdb->delete();

        return redirect()
            ->route('admin.ppdb.index')
            ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
