<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentStatisticRequest;
use App\Models\StudentStatistic;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentStatisticController extends Controller
{
    public function index(): View
    {
        return view('admin.statistics-index', [
            'statistics' => StudentStatistic::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.statistics-form');
    }

    public function store(StoreStudentStatisticRequest $request): RedirectResponse
    {
        StudentStatistic::create($request->safe()->only(['year', 'male_students', 'female_students']));

        return redirect()
            ->route('admin.statistics.index')
            ->with('success', 'Data statistik siswa berhasil disimpan.');
    }

    public function edit(StudentStatistic $statistic): View
    {
        return view('admin.statistics-form', ['statistic' => $statistic]);
    }

    public function update(StoreStudentStatisticRequest $request, StudentStatistic $statistic): RedirectResponse
    {
        $statistic->update($request->safe()->only(['year', 'male_students', 'female_students']));

        return redirect()
            ->route('admin.statistics.edit', $statistic)
            ->with('success', 'Data statistik siswa berhasil diperbarui.');
    }

    public function destroy(StudentStatistic $statistic): RedirectResponse
    {
        $statistic->delete();

        return redirect()
            ->route('admin.statistics.index')
            ->with('success', 'Data statistik siswa berhasil dihapus.');
    }
}
