<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthenticatedRedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未認証ユーザーは管理画面にアクセスするとログインページにリダイレクトされる(): void
    {
        // Act
        $response = $this->get(route('admin.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }
}
