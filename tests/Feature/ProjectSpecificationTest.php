<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectSpecificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_intelligence_api_endpoints_are_available(): void
    {
        $this->getJson('/api/countries')->assertOk();
        $this->getJson('/api/risk')->assertOk();
        $this->getJson('/api/ports')->assertOk();
        $this->getJson('/api/news')->assertOk();
        $this->getJson('/api/currency')->assertOk();
    }

    public function test_only_admin_can_manage_articles_and_published_article_appears_in_news(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('admin.articles.index'))->assertForbidden();

        $this->actingAs($admin)->post(route('admin.articles.store'), [
            'title' => 'Analisis Risiko Pelabuhan',
            'summary' => 'Ringkasan analisis.',
            'content' => 'Isi analisis internal CargoVision.',
            'category' => 'Logistics',
            'status' => 'Published',
        ])->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseHas('articles', ['title' => 'Analisis Risiko Pelabuhan', 'status' => 'Published']);
        $this->actingAs($user)->get(route('news'))->assertOk()->assertSee('Analisis Risiko Pelabuhan');
    }

    public function test_analytics_is_country_risk_intelligence_not_shipment_primary(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Country::create(['name' => 'Indonesia', 'cca2' => 'ID', 'cca3' => 'IDN']);

        $this->actingAs($user)->get(route('analytics'))
            ->assertOk()
            ->assertSee('Supply Chain Risk Analytics')
            ->assertSee('GDP Trend')
            ->assertSee('Inflation Trend')
            ->assertSee('Currency Trend')
            ->assertSee('Risk Trend');
    }
}
