<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\News;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Database\Seeders\SchoolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_school_name(): void
    {
        $this->seed(SchoolSeeder::class);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('SMP Negeri Satu Atap I Sidamulih')
            ->assertSee('20253310');
    }

    public function test_profile_page_lists_active_teachers(): void
    {
        $this->seed(SchoolSeeder::class);

        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('Guru & Tenaga Kependidikan', false)
            ->assertSee('Visi');
    }

    public function test_news_index_shows_only_published_articles(): void
    {
        $published = News::factory()->create([
            'title' => 'Kegiatan Upacara Bendera Hari Senin',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        $draft = News::factory()->create([
            'title' => 'Draf Internal Belum Tayang',
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title);
    }

    public function test_published_article_renders(): void
    {
        $article = News::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('news.show', $article))
            ->assertOk()
            ->assertSee($article->title);
    }

    public function test_unpublished_article_returns_404(): void
    {
        $article = News::factory()->create([
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->get(route('news.show', $article))->assertNotFound();
    }

    public function test_valid_ppdb_payload_creates_registration(): void
    {
        $wave = PpdbWave::create([
            'name' => 'Gelombang 1',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'quota' => 100,
            'is_active' => true,
        ]);

        $payload = [
            'student_name' => 'Budi Santoso',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-05-10',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Kalijati',
            'parent_name' => 'Ahmad Santoso',
            'parent_phone' => '081234567890',
            'address' => 'Dusun Kalijati, Sidamulih',
            'jalur' => 'zonasi',
            'ppdb_wave_id' => $wave->id,
        ];

        $response = $this->post(route('ppdb.store'), $payload);

        $response->assertRedirect(route('ppdb.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('ppdb_registrations', [
            'student_name' => 'Budi Santoso',
            'status' => PpdbRegistration::STATUS_MENUNGGU,
        ]);
        $this->assertMatchesRegularExpression(
            '/^PPDB-\d{4}-\d{4}$/',
            PpdbRegistration::firstOrFail()->registration_number
        );
    }

    public function test_empty_ppdb_payload_fails_validation(): void
    {
        $response = $this->post(route('ppdb.store'), []);

        $response->assertSessionHasErrors([
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
        ]);
        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_contact_message_is_stored(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Siti Aminah',
            'contact' => 'siti@example.com',
            'subject' => 'Info PPDB',
            'message' => 'Kapan pendaftaran dibuka?',
        ]);

        $response->assertRedirect(route('contact.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['name' => 'Siti Aminah']);
    }

    public function test_ppdb_status_check_shows_registration(): void
    {
        $registration = PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0007',
            'student_name' => 'Cek Status',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'P',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Ibu Cek',
            'parent_phone' => '081234567891',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
        ]);

        $this->get(route('ppdb.check'))->assertOk()->assertSee('Cek Status Pendaftaran');

        $this->get(route('ppdb.check', ['nomor' => 'PPDB-2026-0007']))
            ->assertOk()
            ->assertSee('Cek Status')
            ->assertSee(route('ppdb.print', $registration->registration_number), false);

        $this->get(route('ppdb.print', 'PPDB-2026-0007'))
            ->assertOk()
            ->assertSee('Bukti Pendaftaran')
            ->assertSee('Cek Status');
    }

    public function test_ppdb_status_check_rejects_unknown_number(): void
    {
        $this->get(route('ppdb.check', ['nomor' => 'PPDB-2026-9999']))
            ->assertRedirect(route('ppdb.check'))
            ->assertSessionHasErrors('nomor');

        $this->get(route('ppdb.print', 'PPDB-2026-9999'))->assertNotFound();
    }

    public function test_accepted_registration_shows_congratulations(): void
    {
        PpdbRegistration::create([
            'registration_number' => 'PPDB-2026-0010',
            'student_name' => 'Siswa Lolos',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-01-01',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Sidamulih',
            'parent_name' => 'Bapak Lolos',
            'parent_phone' => '081234567892',
            'address' => 'Sidamulih',
            'jalur' => 'zonasi',
            'status' => PpdbRegistration::STATUS_DITERIMA,
        ]);

        $this->get(route('ppdb.check', ['nomor' => 'PPDB-2026-0010']))
            ->assertOk()
            ->assertSee('Selamat atas diterimanya', false)
            ->assertSee('Siswa Lolos');
    }

    public function test_announcements_show_on_home_and_index(): void
    {
        Announcement::create([
            'title' => 'Libur Semester Ganjil',
            'content' => 'Kegiatan belajar libur mulai tanggal sekian.',
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);
        Announcement::create([
            'title' => 'Draf Internal',
            'content' => 'Belum tayang.',
            'is_published' => false,
        ]);

        $this->get(route('home'))->assertOk()->assertSee('Libur Semester Ganjil')->assertDontSee('Draf Internal');
        $this->get(route('announcements'))->assertOk()->assertSee('Libur Semester Ganjil')->assertDontSee('Draf Internal');
    }

    public function test_news_search_and_category_filter(): void
    {
        $category = Category::create(['name' => 'Prestasi', 'slug' => 'prestasi']);
        News::factory()->create([
            'title' => 'Juara Futsal Tingkat Kabupaten',
            'is_published' => true,
            'published_at' => now()->subDay(),
            'category_id' => $category->id,
        ]);
        News::factory()->create([
            'title' => 'Rapat Koordinasi Guru',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('news.index', ['q' => 'Futsal']))
            ->assertOk()
            ->assertSee('Juara Futsal Tingkat Kabupaten')
            ->assertDontSee('Rapat Koordinasi Guru');

        $this->get(route('news.index', ['kategori' => 'prestasi']))
            ->assertOk()
            ->assertSee('Juara Futsal Tingkat Kabupaten')
            ->assertDontSee('Rapat Koordinasi Guru');
    }

    public function test_sitemap_returns_xml_with_urls(): void
    {
        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('content-type', 'text/xml; charset=UTF-8')
            ->assertSee(route('news.index'), false);
    }

    public function test_gallery_page_renders(): void
    {
        $this->seed(SchoolSeeder::class);

        $this->get(route('gallery'))->assertOk()->assertSee('Galeri Kegiatan');
    }
}
