<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Editable setting fields grouped for the form.
     *
     * @return array<string, array{label: string, fields: array<string, array{label: string, type: string, options?: array<string, string>, hint?: string}>}>
     */
    public static function groups(): array
    {
        $text = fn (string $label): array => ['label' => $label, 'type' => 'text'];
        $area = fn (string $label): array => ['label' => $label, 'type' => 'textarea'];

        return [
            'Identitas Sekolah' => [
                'label' => 'Identitas Sekolah',
                'fields' => [
                    'school.name' => $text('Nama Sekolah'),
                    'school.short_name' => $text('Nama Singkat'),
                    'school.npsn' => $text('NPSN'),
                    'school.accreditation' => $text('Akreditasi'),
                    'school.status' => $text('Status'),
                    'school.students_count' => $text('Jumlah Peserta Didik (angka)'),
                ],
            ],
            'Alamat & Kontak' => [
                'label' => 'Alamat & Kontak',
                'fields' => [
                    'school.address' => $text('Alamat Jalan'),
                    'school.village' => $text('Desa/Kelurahan'),
                    'school.district' => $text('Kecamatan'),
                    'school.regency' => $text('Kabupaten'),
                    'school.province' => $text('Provinsi'),
                    'school.phone' => $text('Telepon/HP'),
                    'school.email' => $text('Email'),
                    'school.maps_embed' => [...$area('URL Sematan Google Maps (src iframe)'), 'hint' => 'Buka Google Maps → Bagikan → Sematkan peta → salin alamat URL pada atribut src iframe.'],
                ],
            ],
            'Profil & Sambutan' => [
                'label' => 'Profil & Sambutan',
                'fields' => [
                    'school.description' => $area('Deskripsi Singkat'),
                    'school.history' => $area('Sejarah'),
                    'school.vision' => $area('Visi'),
                    'school.mission' => $area('Misi'),
                    'school.principal_name' => $text('Nama Kepala Sekolah'),
                    'school.principal_greeting' => $area('Sambutan Kepala Sekolah'),
                ],
            ],
            'PPDB' => [
                'label' => 'PPDB',
                'fields' => [
                    'ppdb.is_open' => ['label' => 'Status Pendaftaran', 'type' => 'select', 'options' => ['1' => 'Dibuka', '0' => 'Ditutup']],
                    'ppdb.year' => $text('Tahun Ajaran (mis. 2026/2027)'),
                    'ppdb.quota' => $text('Kuota Kursi'),
                    'ppdb.info' => $area('Informasi Pendaftaran'),
                ],
            ],
            'Media Sosial' => [
                'label' => 'Media Sosial',
                'fields' => [
                    'social.facebook' => $text('Facebook (URL)'),
                    'social.instagram' => $text('Instagram (URL)'),
                    'social.youtube' => $text('YouTube (URL)'),
                ],
            ],
        ];
    }

    public function index(): View
    {
        return view('admin.settings', [
            'groups' => self::groups(),
            'values' => Setting::pluck('value', 'key')->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:5000'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Pengaturan berhasil disimpan dan langsung tampil di halaman publik.');
    }
}
