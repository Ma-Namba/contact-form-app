<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function お問い合わせ一覧をJSON形式で取得できる(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);

        $contact_id = $contact->id;

        $response1 = $this->getJson('/api/v1/contacts');
        $response1->assertStatus(200);
        $response1->assertJsonCount(1, 'data');

        $response2 = $this->getJson("/api/v1/contacts/{$contact_id}");
        $response2->assertStatus(200);
    }

    /** @test */
    public function お問い合わせ一覧のJSONレスポンス構造が正しい(): void
    {
        // Arrange
        $category = Category::factory()->create();
        Contact::factory()->create([
            'category_id' => $category->id,
        ]);

        // Act
        $response = $this->getJson('/api/v1/contacts');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'tel',
                        'detail',
                        'date',
                ],
            ],
        ]);
    }

    /** @test */
    public function お問い合わせ一覧のJSONレスポンス内容が正しい(): void
    {
        // Arrange
        $category = Category::factory()->create(['content' => 'テストカテゴリー']);
        $contact = Contact::factory()->create([
            'category_id' => $category->id,
            'detail' => 'お問い合わせ試験'
        ]);

        // Act
        $response = $this->getJson('/api/v1/contacts');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $contact->id,
            'detail' => 'お問い合わせ試験',
        ]);
    }

    /** @test */
    public function 特定のお問い合わせをJSON形式で取得できる(): void
    {
        // Arrange
        $category = Category::factory()->create(['content' => 'テストカテゴリー']);
        $contact = Contact::factory()->create([
            'category_id' => $category->id,
            'detail' => 'テスト',
        ]);

        // Act
        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'detail' => 'テスト',
        ]);
    }

    /** @test */
    public function 特定のお問い合わせのJSONレスポンス内容が正しい(): void
    {
        // Arrange
        $category = Category::factory()->create(['content' => '仕事']);
        $task = Contact::factory()->create([
            'category_id' => $category->id,
            'detail' => 'これはテストです',
        ]);

        // Act
        $response = $this->getJson("/api/v1/contacts/{$task->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'detail' => 'これはテストです',
        ]);

    }

    /** @test */
    public function 存在しないお問い合わせIDで404エラーを返す(): void
    {
        // Act
        $response = $this->getJson('/api/v1/contacts/99999');

        // Assert
        $response->assertNotFound(); // 404
    }

    /** @test */
    public function 無効なタスクIDで404エラーを返す(): void
    {
        // Act
        $response = $this->getJson('/api/v1/contacts/invalid');

        // Assert
        $response->assertStatus(404);
    }
}

