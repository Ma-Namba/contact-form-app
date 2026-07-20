<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_お問い合わせフォームにカテゴリーとタグが表示される(): void
    {
        $category = Category::factory()->create([
            'content' => 'カテゴリー',
        ]);

        $tag = Tag::factory()->create([
            'name' => 'タグ',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('カテゴリー')
            ->assertSee('タグ');
    }

    public function test_お問い合わせフォーム確認ページが表示されお問い合わせが保存されサンクスページが表示される():void
    {
        $category = Category::factory()->create([]);

        $request = [
            'category_id' => $category->id,
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel' => '08012345678',
            'address' => 'aaa',
            'building' => '',
            'detail' => 'aaaaa',
        ];

        $response = $this->post(route('contact.confirm',$request));
        $response->assertStatus(200);

        $response = $this->post(route('contact.thanks', $request));
        $response = $response->assertViewIs('contact.thanks');

        $response = $this->assertDatabaseHas(Contact::class, [
            'address' => 'aaa',
        ]);
    }

    public function test_バリテーションエラー時は(): void
    {
        $category = Category::factory()->create([]);

        $request =[
            'category_id' => $category->id,
            'first_name' => '',
            'last_name' => '太郎',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel' => '08012345678',
            'address' => 'aaa',
            'building' => '',
            'detail' => 'aaaaa',
        ];

        $response = $this->post(route('contact.confirm', $request));
        $response->assertSessionHasErrors('first_name');
    }
}
