<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class SearchTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        // コンタクトデータを10件作成
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $contacts = Contact::factory()->count(5)->create([
            'first_name' => "花子",
            'last_name' => "鈴木",
            'gender' => '1',
            'category_id' => $category1->id,
        ]);
        $contacts = Contact::factory()->count(2)->create([
            'first_name' => "太郎",
            'last_name' => "佐藤",
            'gender' => '2',
            'category_id' => $category2->id,
        ]);
        $contacts = Contact::all();

        // ユーザーデータを作成・ログイン
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response = $this->actingAs($user)->get('/admin');
        $contacts = $response->viewData('contacts');

        // 検索キーワードを指定してリクエストを送信
        $response = $this->actingAs($user)->get('/admin?keyword=鈴木&gender=0&category_id=&date=');
        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(5, $contacts);

        $response = $this->actingAs($user)->get('/admin');
        $contacts = $response->viewData('contacts');

        // カテゴリーを指定してリクエストを送信
        $response = $this->actingAs($user)->get('/admin?keyword=&gender=0&category_id=1&date=');
        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(5, $contacts);

        $response = $this->actingAs($user)->get('/admin');
        $contacts = $response->viewData('contacts');

        // 性別を指定してリクエストを送信
        $response = $this->actingAs($user)->get('/admin?keyword=&gender=1&category_id=&date=');
        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(5, $contacts);

        // 日付を指定してリクエストを送信
        $response = $this->actingAs($user)->get('/admin?keyword=&gender=0&category_id=&date=2026-07-22');
        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(7, $contacts);

    }
}
