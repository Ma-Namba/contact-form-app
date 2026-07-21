<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class ContactValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    /** @test */
    public function 正しいデータでバリデーションを通過すること(): void
    {
        $category = Category::factory()->create();
        $response = $this->post('/contact', [
            'category_id' => $category->id,
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => '1',
            'email' => 'sample@email.com',
            'tel' => '08011111111',
            'address' => 'aa県aa市aaa町1234-5',
            'building' => 'aaaビル',
            'detail' => 'お問い合わせ内容',
        ]);
        $response->assertValid();
    }

    /** @test */
    public function 必須項目を入力していないとバリデーションを通過しないこと(): void
    {
        $category = Category::factory()->create();
        $response = $this->post('/contacts/confirm', [
            'category_id' => $category->id,
            'first_name' => '',
            'last_name' => '',
            'gender' => '',
            'email' => '',
            'tel' => '',
            'address' => '',
            'building' => 'aaaビル',
            'detail' => '',
        ]);

        $response->assertValid(['building']);
        $response->assertInvalid(['first_name']);
        $response->assertInvalid(['last_name']);
        $response->assertInvalid(['gender']);
        $response->assertInvalid(['email']);
        $response->assertInvalid(['tel']);
        $response->assertInvalid(['address']);
        $response->assertInvalid(['detail']);
    }

    /** @test */
    public function 文字数制限を超えた場合バリデーションエラーとなること():void
    {
        $category = Category::factory()->create();
        $response = $this->post('/contacts/confirm', [
            'category_id' => $category->id,
            'first_name' => str_repeat('a', 256),
            'last_name' => str_repeat('a', 256),
            'gender' => '4',
            'email' => str_repeat('a', 256),
            'tel' => str_repeat('0', 12),
            'address' => str_repeat('a', 256),
            'building' => str_repeat('a', 256),
            'detail' => str_repeat('a', 121),
        ]);

        $response->assertInvalid(['building']);
        $response->assertInvalid(['first_name']);
        $response->assertInvalid(['last_name']);
        $response->assertInvalid(['gender']);
        $response->assertInvalid(['email']);
        $response->assertInvalid(['tel']);
        $response->assertInvalid(['address']);
        $response->assertInvalid(['detail']);
    }

    /** @test */
    public function 文字数限界値でバリテーションを通過すること():void
    {
        $category = Category::factory()->create();
        $response = $this->post('/contacts/confirm', [
            'category_id' => $category->id,
            'first_name' => str_repeat('a', 255),
            'last_name' => str_repeat('a', 255),
            'gender' => '3',
            'email' => str_repeat('a', 245).'@email.com',
            'tel' => str_repeat('0', 11),
            'address' => str_repeat('a', 255),
            'building' => str_repeat('a', 255),
            'detail' => str_repeat('a', 120),
        ]);
        $response->assertValid();
    }
}
