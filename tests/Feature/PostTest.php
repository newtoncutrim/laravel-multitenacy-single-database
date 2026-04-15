<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tenat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->post('/posts', [
                'title' => 'Primeiro post',
                'content' => 'Conteudo do primeiro post.',
            ])
            ->assertRedirect('/posts');

        $this->assertDatabaseHas('posts', [
            'tenat_id' => $user->tenat_id,
            'user_id' => $user->id,
            'title' => 'Primeiro post',
        ]);
    }

    public function test_posts_page_shows_only_posts_from_the_user_tenant(): void
    {
        $user = $this->createUser();
        $otherUser = $this->createUser('Outra Empresa', 'outra@example.com');

        Post::create([
            'tenat_id' => $user->tenat_id,
            'user_id' => $user->id,
            'title' => 'Post visivel',
            'content' => 'Este aparece.',
        ]);

        Post::create([
            'tenat_id' => $otherUser->tenat_id,
            'user_id' => $otherUser->id,
            'title' => 'Post escondido',
            'content' => 'Este nao aparece.',
        ]);

        $this->actingAs($user)
            ->get('/posts')
            ->assertOk()
            ->assertSee('Post visivel')
            ->assertDontSee('Post escondido');
    }

    public function test_authenticated_user_can_delete_own_tenant_post(): void
    {
        $user = $this->createUser();
        $post = Post::create([
            'tenat_id' => $user->tenat_id,
            'user_id' => $user->id,
            'title' => 'Post para apagar',
            'content' => 'Conteudo.',
        ]);

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertRedirect('/posts');

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    private function createUser(string $tenantName = 'Acme', string $email = 'ana@example.com'): User
    {
        $tenat = Tenat::create(['name' => $tenantName]);

        return User::create([
            'tenat_id' => $tenat->id,
            'name' => 'Ana Silva',
            'email' => $email,
            'password' => 'password',
        ]);
    }
}
