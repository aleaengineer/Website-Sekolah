<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_has_canonical_json_ld_and_unique_description(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('home').'"', false)
            ->assertSee('"@type":"School"', false)
            ->assertSee('20253310', false)
            ->assertSee('<meta name="description"', false);
    }

    public function test_pages_have_distinct_descriptions(): void
    {
        $this->seed(\Database\Seeders\SchoolSeeder::class);

        $descriptions = [];

        foreach (['home', 'profile', 'academic', 'news.index', 'announcements', 'gallery', 'ppdb.index', 'contact.index'] as $route) {
            $html = $this->get(route($route))->assertOk()->getContent();

            preg_match('/<meta name="description" content="([^"]*)"/', $html, $matches);
            $this->assertNotEmpty($matches[1] ?? null, "Halaman {$route} tanpa deskripsi");
            $descriptions[$route] = $matches[1];
        }

        $this->assertGreaterThan(1, count(array_unique($descriptions)), 'Semua deskripsi halaman identik');
    }

    public function test_news_detail_uses_cover_for_og_image(): void
    {
        Storage::fake('public');

        $article = News::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'excerpt' => 'Ringkasan khusus artikel ini.',
            'cover_image' => UploadedFile::fake()->image('sampul.jpg')->store('news', 'public'),
        ]);

        $this->get(route('news.show', $article))
            ->assertOk()
            ->assertSee('Ringkasan khusus artikel ini.', false)
            ->assertSee($article->cover_image, false);
    }

    public function test_agenda_detail_has_unique_description_and_og_image(): void
    {
        Agenda::create([
            'title' => 'Judul Agenda Unik SEO',
            'slug' => 'judul-agenda-unik-seo',
            'description' => 'Deskripsi agenda yang unik untuk pengujian SEO.',
            'start_at' => now()->addDays(3),
            'is_published' => true,
        ]);

        $this->get(route('agendas.show', 'judul-agenda-unik-seo'))
            ->assertOk()
            ->assertSee('Deskripsi agenda yang unik untuk pengujian SEO.', false);
    }

    public function test_internal_pages_are_noindex(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->get(route('search', ['q' => 'futsal']))->assertOk()->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertHeader('content-type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin', false)
            ->assertSee(route('sitemap'), false);
    }

    public function test_sitemap_has_lastmod_on_static_pages(): void
    {
        News::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee('<lastmod>', false)
            ->assertSee(route('news.index'), false);
    }
}
