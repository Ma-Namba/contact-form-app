<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    /** @test */
    public function 管理者はカテゴリ付きのお問い合わせ詳細を取得できる(): void
    {
        $user = User::factory()->create([
            'email' => 'sample@email.com',
            'password' => bcrypt('password123'),
        ]);
        $category = Category::factory()->create([
            'content' => 'その他'
        ]);
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.show',$contact));
        $response->assertStatus(200);

        $response->assertSee('その他');
    }

    /** @test */
    public function 管理者でない場合お問い合わせ詳細を取得できない():void
    {
        $category = Category::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->get(route('admin.show', $contact));
        $response->assertStatus(302);
    }

    /** @test */
    public function 選択したお問い合わせのレコードが正常に削除されadminにリダイレクトされる():void
    {
        $user = User::factory()->create([
            'email' => 'sample@email.com',
            'password' => bcrypt('password123'),
        ]);
        $category = Category::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user)->delete(route('contact.delete', ['contact' => $contact->id]));
        $response->assertStatus(302);
        $response->assertRedirect('/admin');
        $response = $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}


