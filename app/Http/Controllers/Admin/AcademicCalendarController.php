<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAcademicCalendarRequest;
use App\Models\AcademicCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicCalendarController extends Controller
{
    public function index(): View
    {
        return view('admin.calendars-index', [
            'entries' => AcademicCalendar::orderBy('start_date')->paginate(15),
            'categories' => AcademicCalendar::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('admin.calendars-form', ['categories' => AcademicCalendar::CATEGORIES]);
    }

    public function store(StoreAcademicCalendarRequest $request): RedirectResponse
    {
        $entry = AcademicCalendar::create($this->payload($request));

        return redirect()
            ->route('admin.calendars.edit', $entry)
            ->with('success', 'Kalender pendidikan berhasil disimpan.');
    }

    public function edit(AcademicCalendar $calendar): View
    {
        return view('admin.calendars-form', [
            'calendar' => $calendar,
            'categories' => AcademicCalendar::CATEGORIES,
        ]);
    }

    public function update(StoreAcademicCalendarRequest $request, AcademicCalendar $calendar): RedirectResponse
    {
        $calendar->update($this->payload($request));

        return redirect()
            ->route('admin.calendars.edit', $calendar)
            ->with('success', 'Kalender pendidikan berhasil diperbarui.');
    }

    public function destroy(AcademicCalendar $calendar): RedirectResponse
    {
        $calendar->delete();

        return redirect()
            ->route('admin.calendars.index')
            ->with('success', 'Kalender pendidikan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreAcademicCalendarRequest $request): array
    {
        $validated = $request->safe()->except(['is_published']);
        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
