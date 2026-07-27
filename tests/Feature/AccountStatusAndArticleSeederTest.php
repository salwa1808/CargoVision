<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\ArticleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountStatusAndArticleSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => 'secret123',
            'status' => 'Inactive',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_active_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'active@example.com',
            'password' => 'secret123',
            'status' => 'Active',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_article_seeder_creates_published_articles_once(): void
    {
        User::factory()->create(['role' => 'admin']);

        $this->seed(ArticleSeeder::class);
        $this->seed(ArticleSeeder::class);

        $this->assertSame(4, Article::count());
        $this->assertSame(4, Article::where('status', 'Published')->count());
    }
}
