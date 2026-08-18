<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryAdminLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_uses_admin_layout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/admin/gallery');

        $response->assertOk();
        $response->assertSee('MC ADMIN');
        $response->assertSee('Galería de Imágenes');
        $response->assertSee('Nueva Imagen');
        $response->assertDontSee('Monte Carmelo</a>', false);
    }
}
