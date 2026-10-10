<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSlideTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_home_shows_fallback_hero_without_slides(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Daftar PPDB')
            ->assertDontSee('hero-slide');
    }

    public function test_home_shows_active_slides_and_hides_inactive(): void
    {
        HeroSlide::create([
            'title' => 'PPDB Telah Dibuka',
            'subtitle' => 'Ayo bergabung bersama kami.',
            'button_text' => 'Daftar',
            'button_url' => '/ppdb',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        HeroSlide::create([
            'title' => 'Draf Slide Internal',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('PPDB Telah Dibuka')
            ->assertSee('hero-slide', false)
            ->assertDontSee('Draf Slide Internal');
    }

    public function test_admin_can_manage_hero_slides(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.heroes.store'), [
            'title' => 'Selamat Datang',
            'subtitle' => 'Website resmi sekolah.',
            'button_text' => 'Profil',
            'button_url' => '/profil',
            'sort_order' => 1,
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('hero.jpg'),
        ])->assertRedirect();

        $slide = HeroSlide::where('title', 'Selamat Datang')->firstOrFail();
        Storage::disk('public')->assertExists($slide->image);

        $this->get(route('home'))->assertOk()->assertSee('Selamat Datang');
    }

    public function test_guru_is_forbidden_from_heroes(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)->get(route('admin.heroes.index'))->assertForbidden();
    }
}
