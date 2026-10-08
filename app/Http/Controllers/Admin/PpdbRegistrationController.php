<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PpdbRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PpdbRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = [
            PpdbRegistration::STATUS_MENUNGGU => 'Menunggu',
            PpdbRegistration::STATUS_DIVERIFIKASI => 'Diverifikasi',
            PpdbRegistration::STATUS_DITERIMA => 'Diterima',
            PpdbRegistration::STATUS_DITOLAK => 'Ditolak',
        ];

        $registrations = PpdbRegistration::latest()
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->input('q'), fn ($query, $q) => $query->where(fn ($sub) => $sub
                ->where('student_name', 'like', "%{$q}%")
                ->orWhere('registration_number', 'like', "%{$q}%")))
            ->paginate(15)
            ->withQueryString();

        return view('admin.ppdb-index', [
            'registrations' => $registrations,
            'statuses' => $statuses,
        ]);
    }

    public function show(PpdbRegistration $ppdb): View
    {
        return view('admin.ppdb-show', ['registration' => $ppdb]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $status = $request->validate([
            'status' => ['nullable', 'in:menunggu,diverifikasi,diterima,ditolak'],
        ])['status'] ?? null;

        $suffix = $status ? "-{$status}" : '';

        return Excel::download(
            new PpdbRegistrationsExport($status),
            'ppdb'.now()->format('Y-m-d')."{$suffix}.xlsx"
        );
    }

    public function update(Request $request, PpdbRegistration $ppdb): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,diverifikasi,diterima,ditolak'],
        ]);

        $ppdb->update($validated);

        return redirect()
            ->route('admin.ppdb.show', $ppdb)
            ->with('success', "Status pendaftaran {$ppdb->registration_number} diubah menjadi {$validated['status']}.");
    }

    public function destroy(PpdbRegistration $ppdb): RedirectResponse
    {
        $ppdb->delete();

        return redirect()
            ->route('admin.ppdb.index')
            ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
