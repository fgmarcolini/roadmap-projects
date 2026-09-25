<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\RolesAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionSeeder::class);
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();

        $this->user->assignRole('writer');
        $this->admin->assignRole('admin');
    }

    public function test_index_should_show_articles()
    {
        // arrange
        $article = Article::factory()->create();

        // act
        $response = $this->actingAs($this->user)->get('/articles');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('articles', function ($collection) use ($article) {
            return $collection->contains($article);
        });
    }

    public function test_index_should_show_empty_when_no_articles_in_db()
    {
        // act
        $response = $this->actingAs($this->user)->get('/articles');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('articles', function ($collection) {
            return $collection->isEmpty();
        });
    }

    public function test_admin_should_show_articles_if_authenticated()
    {
        // arrange
        $article = Article::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // act
        $response = $this->actingAs($this->user)->get('/admin');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('articles', function ($collection) use ($article) {
            return $collection->contains($article);
        });
    }

    public function test_admin_should_redirect_to_login_if_not_authenticated()
    {
        // act
        $response = $this->actingAsGuest()->get('/admin');

        // assert
        $response->assertRedirect('/login');
    }

    public function test_admin_should_show_empty_when_no_articles_in_db()
    {
        // act
        $response = $this->actingAs($this->user)->get('/admin');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('articles', function ($collection) {
            return $collection->isEmpty();
        });
    }

    public function test_create_should_return_view_if_authenticated()
    {
        // act
        $response = $this->actingAs($this->user)->get(route('articles.create'));

        // assert
        $response->assertStatus(200);
        $response->assertSee('Publish');
    }

    public function test_create_should_redirect_to_login_if_not_authenticated()
    {
        // act
        $response = $this->actingAsGuest()->get(route('articles.create'));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_store_should_save_article_and_redirect_to_admin_if_auth_and_valid_data()
    {
        // arrange
        $articleData = [
            'title' => 'some title',
            'dop' => '2026-09-21',
            'content' => 'some content',
        ];

        // act
        $response = $this->actingAs($this->admin)->post(route('articles.store'), $articleData);

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('articles.admin'));
        $response->assertSessionHas('success', 'Article created successfully!');
        $this->assertDatabaseHas('articles', ['title' => 'some title']);
    }

    public function test_edit_should_return_view_if_authenticated()
    {
        // arrange
        $article = Article::factory()->create();

        // act
        $response = $this->actingAs($this->admin)->get(route('articles.edit', $article));

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('article', $article);
    }

    public function test_update_should_update_article_and_redirect_if_auth_and_valid_data()
    {
        // arrange
        $article = Article::factory()->create([
            'title' => 'old title',
            'user_id' => $this->user->id,
        ]);

        $updatedData = [
            'title' => 'new title',
            'dop' => '2026-09-22',
            'content' => 'new content',
        ];

        // act
        $response = $this->actingAs($this->user)->put(route('articles.update', $article), $updatedData);

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('articles.admin'));
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => 'new title']);
    }

    public function test_destroy_should_delete_article_if_authenticated_and_authorized()
    {
        // arrange
        $article = Article::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // act
        $response = $this->actingAs($this->user)->delete(route('articles.destroy', $article));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('articles.admin'));
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
