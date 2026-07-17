<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactRelationTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_1つのお問い合わせが特定のカテゴリに属し、複数のタグと同期（sync）できる(): void
    {
        // コンタクトデータを1件作成
        $category = Category::factory()->create();
        $contact = Contact::factory()->create([
            'category_id' => $category->id
        ]);

        // タグデータを5件作成
        $tag = Tag::factory()->count(5)->create();
        $tag_id = Tag::pluck('id')->toArray();

        // コンタクト1件とタグ5件を紐づけたcontact_tagレコードを5件作成
        $contact->tags()->sync($tag_id);
        $count = DB::table('contact_tag')->where('contact_id',$contact->id)->count();

        // 保存されたデータが5件あるか確認する
        $this->assertEquals(5, $count);
    }
}
