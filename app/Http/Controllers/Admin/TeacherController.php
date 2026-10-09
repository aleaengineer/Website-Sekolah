<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TeachersExport;
use App\Exports\TeachersTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Imports\TeachersImport;
use App\Models\ActivityLog;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherController extends Controller
{
    public function index(): View
    {
        return view('admin.teachers-index', [
            'teachers' => Teacher::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.teachers-form');
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacher = Teacher::create($this->payload($request, new Teacher));

        return redirect()
            ->route('admin.teachers.edit', $teacher)
            ->with('success', 'Data guru berhasil disimpan.');
    }

    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers-form', ['teacher' => $teacher]);
    }

    public function update(StoreTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $teacher->update($this->payload($request, $teacher));

        return redirect()
            ->route('admin.teachers.edit', $teacher)
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function template(): BinaryFileResponse
    {
        return Excel::download(new TeachersTemplateExport, 'template-import-guru.xlsx');
    }

    public function export(): BinaryFileResponse
    {
        ActivityLog::record(ActivityLog::ACTION_EXPORT, 'mengekspor data guru ke Excel');

        return Excel::download(new TeachersExport, 'data-guru-'.now()->format('Y-m-d').'.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'import' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new TeachersImport;
        Excel::import($import, $validated['import']);

        ActivityLog::record(ActivityLog::ACTION_IMPORT, "mengimpor data guru: {$import->imported} baru, {$import->skipped} dilewati");

        $message = "{$import->imported} data guru baru ditambahkan, {$import->skipped} baris dilewati (kosong/nama sudah ada).";

        if ($import->failures()->isNotEmpty()) {
            $details = $import->failures()
                ->map(fn ($failure) => "Baris {$failure->row()}: ".implode(', ', $failure->errors()))
                ->all();

            return redirect()
                ->route('admin.teachers.index')
                ->with('success', $message)
                ->with('import_errors', $details);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', $message);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreTeacherRequest $request, Teacher $teacher): array
    {
        $validated = $request->safe()->except(['photo', 'is_active']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        return $validated;
    }
}
