<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Contact;

class CategoryContactRelationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    /** @test */
    public function test_コンタクトがカテゴリーに正しく紐づいていることの検証() : void
    {
        // 準備：カテゴリーに紐づけた5件のコンタクトを作成
        $category = Category::factory()->create();
        $contact = Contact::factory()->count(5)->create([
            'category_id' => $category->id
            ]);

        // 2. カテゴリーに紐づいたコンタクトが5件あるかを確認
        $count = Contact::where('category_id', $category->id)->count();
        $this->assertEquals(5, $count);
    }
}
