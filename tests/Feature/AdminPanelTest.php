<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\StudentStatistic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_operator_is_forbidden_from_user_management(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $this->actingAs($operator)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($operator)->get(route('admin.news.index'))->assertOk();
    }

    public function test_guru_is_limited_to_news_and_galleries(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($guru)->get(route('admin.news.index'))->assertOk();
        $this->actingAs($guru)->get(route('admin.galleries.index'))->assertOk();
        $this->actingAs($guru)->get(route('admin.teachers.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.settings'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_admin_can_create_user_with_role(): void
    {
        $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'Operator Baru',
            'email' => 'operator@satap1sidamulih.sch.id',
            'role' => User::ROLE_OPERATOR,
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'operator@satap1sidamulih.sch.id',
            'role' => User::ROLE_OPERATOR,
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_login_and_see_dashboard(): void
    {
        $admin = $this->admin();

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard');
    }

    public function test_login_rejects_wrong_password(): void
    {
        $admin = $this->admin();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'salah-sandi',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_publish_news_that_appears_on_public_page(): void
    {
        $this->actingAs($this->admin())->post(route('admin.news.store'), [
            'title' => 'Juara Lomba Futsal Antar Sekolah',
            'excerpt' => 'Tim futsal sekolah meraih juara pertama.',
            'body' => '<p>Tim futsal sekolah meraih juara pertama.</p>',
            'is_published' => '1',
        ])->assertRedirect();

        $article = News::where('title', 'Juara Lomba Futsal Antar Sekolah')->firstOrFail();

        $this->get(route('news.index'))->assertOk()->assertSee($article->title);
    }

    public function test_news_store_rejects_empty_title(): void
    {
        $this->actingAs($this->admin())->post(route('admin.news.store'), [
            'title' => '',
            'body' => '<p>Isi.</p>',
        ])->assertSessionHasErrors('title');

        $this->assertDatabaseCount('news', 0);
    }

    public function test_admin_can_upload_news_cover(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.news.store'), [
            'title' => 'Berita Bergambar Sampul',
            'body' => '<p>Isi berita.</p>',
            'cover_image' => UploadedFile::fake()->image('sampul.jpg'),
        ])->assertRedirect();

        $article = News::where('title', 'Berita Bergambar Sampul')->firstOrFail();

        Storage::disk('public')->assertExists($article->cover_image);
    }

    public function test_admin_can_update_settings(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'settings' => ['school.phone' => '(0265) 123456'],
        ])->assertRedirect(route('admin.settings'));

        $this->assertDatabaseHas('settings', [
            'key' => 'school.phone',
            'value' => '(0265) 123456',
        ]);
    }

    public function test_admin_can_import_teachers_from_excel(): void
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([['nama', 'jabatan', 'mapel', 'urutan', 'aktif']]);
        $sheet->fromArray([['Guru Impor Satu', 'Guru', 'IPA', 1, 1]], null, 'A2', true);
        $sheet->fromArray([['Guru Impor Dua', 'Tata Usaha', null, 2, null]], null, 'A3', true);
        $sheet->getCell('E3')->setValueExplicit(0, DataType::TYPE_NUMERIC);
        $sheet->fromArray([['Guru Impor Satu', 'Guru', 'IPS', 3, 1]], null, 'A4', true);
        $path = tempnam(sys_get_temp_dir(), 'guru').'.xlsx';
        (new Xlsx($spreadsheet))->save($path);

        $this->actingAs($this->admin())->post(route('admin.teachers.import'), [
            'import' => new UploadedFile($path, 'guru.xlsx', null, null, true),
        ])->assertRedirect(route('admin.teachers.index'));

        $this->assertDatabaseHas('teachers', ['name' => 'Guru Impor Satu', 'subject' => 'IPA']);
        $this->assertDatabaseHas('teachers', ['name' => 'Guru Impor Dua', 'is_active' => false]);
        $this->assertDatabaseCount('teachers', 2);
    }

    public function test_plain_maps_share_url_is_normalized_for_embed(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'settings' => ['school.maps_embed' => 'https://www.google.com/maps?q=-7.5718,108.5896'],
        ])->assertRedirect();

        $this->get(route('profile'))->assertOk()->assertSee('output=embed', false);
    }

    public function test_place_maps_url_is_normalized_to_coordinates(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'settings' => ['school.maps_embed' => 'https://www.google.com/maps/place/7%C2%B034/@-7.5718,108.5870251,1197m/data=!3m2?entry=ttu'],
        ])->assertRedirect();

        $this->get(route('profile'))->assertOk()->assertSee('https://maps.google.com/maps?q=-7.5718,108.5870251', false);
    }

    public function test_admin_can_export_teachers_to_excel(): void
    {
        $this->actingAs($this->admin())->get(route('admin.teachers.export'))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_admin_can_export_ppdb_registrations_to_excel(): void
    {
        $this->actingAs($this->admin())->get(route('admin.ppdb.export'))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Nama Baru',
            'current_password' => 'password',
            'password' => 'sandi-baru-123',
            'password_confirmation' => 'sandi-baru-123',
        ])->assertRedirect(route('admin.profile.edit'));

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'name' => 'Nama Baru']);
        $this->post(route('logout'));
        $this->post(route('login.store'), ['email' => $admin->email, 'password' => 'sandi-baru-123'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_profile_update_rejects_wrong_current_password(): void
    {
        $this->actingAs($this->admin())->put(route('admin.profile.update'), [
            'name' => 'Tetap',
            'current_password' => 'keliru',
            'password' => 'sandi-baru-123',
            'password_confirmation' => 'sandi-baru-123',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_sidebar_highlights_active_menu(): void
    {
        $admin = $this->admin();

        foreach (['admin.dashboard', 'admin.news.index', 'admin.teachers.index', 'admin.settings'] as $route) {
            $this->actingAs($admin)->get(route($route))
                ->assertOk()
                ->assertSee('bg-white/10 text-white shadow', false);
        }
    }

    public function test_admin_can_manage_student_statistics(): void
    {
        $this->actingAs($this->admin())->post(route('admin.statistics.store'), [
            'year' => 2025,
            'male_students' => 80,
            'female_students' => 70,
        ])->assertRedirect(route('admin.statistics.index'));

        $this->assertDatabaseHas('student_statistics', ['year' => 2025]);

        $this->actingAs($this->admin())->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Pertumbuhan Siswa per Tahun')
            ->assertSee('2025');
    }

    public function test_chart_bars_scale_with_totals(): void
    {
        StudentStatistic::create(['year' => 2024, 'male_students' => 20, 'female_students' => 80]);
        StudentStatistic::create(['year' => 2025, 'male_students' => 120, 'female_students' => 80]);

        $this->actingAs($this->admin())->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('style="height: 100%"', false)
            ->assertSee('style="height: 50%"', false);
    }

    public function test_statistics_rejects_duplicate_year(): void
    {
        StudentStatistic::create(['year' => 2025, 'male_students' => 80, 'female_students' => 70]);

        $this->actingAs($this->admin())->post(route('admin.statistics.store'), [
            'year' => 2025,
            'male_students' => 10,
            'female_students' => 10,
        ])->assertSessionHasErrors('year');
    }

    public function test_guru_is_forbidden_from_student_statistics(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.statistics.index'))->assertForbidden();
    }

    public function test_admin_can_manage_announcements(): void
    {
        $this->actingAs($this->admin())->post(route('admin.announcements.store'), [
            'title' => 'Upacara Hari Senin',
            'content' => 'Seluruh siswa wajib mengikuti upacara.',
            'is_published' => '1',
            'is_pinned' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('announcements', ['title' => 'Upacara Hari Senin']);
        $this->get(route('home'))->assertSee('Upacara Hari Senin');
    }

    public function test_admin_can_manage_categories(): void
    {
        $this->actingAs($this->admin())->post(route('admin.categories.store'), [
            'name' => 'Ekstrakurikuler',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['slug' => 'ekstrakurikuler']);
    }

    public function test_guru_is_forbidden_from_announcements_and_categories(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.announcements.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_panitia_can_run_ppdb_workflow_only(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_PANITIA]);

        foreach (['admin.dashboard', 'admin.ppdb.index', 'admin.waves.index', 'admin.jalurs.index', 'admin.messages.index', 'admin.profile.edit'] as $route) {
            $this->actingAs($panitia)->get(route($route))->assertOk();
        }

        foreach (['admin.news.index', 'admin.galleries.index', 'admin.teachers.index', 'admin.announcements.index', 'admin.settings', 'admin.users.index', 'admin.activity-logs.index', 'admin.backups.index'] as $route) {
            $this->actingAs($panitia)->get(route($route))->assertForbidden();
        }
    }

    public function test_admin_can_create_panitia_user(): void
    {
        $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'Panitia PPDB',
            'email' => 'panitia@satap1sidamulih.sch.id',
            'role' => User::ROLE_PANITIA,
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'panitia@satap1sidamulih.sch.id',
            'role' => User::ROLE_PANITIA,
        ]);
    }
}
