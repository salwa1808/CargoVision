<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_is_managed_from_settings_without_duplicate_sidebar_toggle(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('settings'))
            ->assertOk()
            ->assertDontSee('id="darkModeBtn"', false)
            ->assertSee('id="themeSelect"', false)
            ->assertSee('CargoVision')
            ->assertSee('Supply Chain Risk Intelligence')
            ->assertSee('html.theme-light', false)
            ->assertSee("localStorage.setItem('theme'", false);
    }
}
