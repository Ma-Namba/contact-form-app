<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class PaginationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_検索結果一覧が7件ごとにページネーションされている(): void
    {

        // コンタクトデータを10件作成
        $categories = Category::factory()->count(5)->create();
        $contacts = Contact::factory()->count(5)->create([
            'first_name' => '花子', 'last_name' => "鈴木"]);
        $contacts = Contact::factory()->count(5)->create([
            'first_name' => '太郎', 'last_name' => '佐藤']);
        $contacts = Contact::all();

        // ユーザーデータを作成・ログイン
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 2ページ目のデータ数が10-7=3であることを確認
        $response = $this->actingAs($user)->get('/admin?page=2');
        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(3, $contacts);

    }

}
