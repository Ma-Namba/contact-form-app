<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class TagRelationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_中間テーブルを介して、1つのタグが複数のお問い合わせに紐づいている(): void
    {
        // コンタクトデータを3件作成する
        $category = Category::factory()->create();
        $contact1 = Contact::factory()->create();
        $contact2 = Contact::factory()->create();
        $contact3 = Contact::factory()->create();

        // タグデータを1件作成する
        $tag = Tag::factory()->create();

        // contact_tagテーブルにコンタクトデータ3件タグデータ1件で3件のレコードを作成する
        DB::table('contact_tag')->insert([[
            'tag_id' => $tag->id,
            'contact_id' => $contact1->id],
            [
            'tag_id' => $tag->id,
            'contact_id' => $contact2->id],
            [
            'tag_id' => $tag->id,
            'contact_id' => $contact3->id
            ],]);

        $count = DB::table('contact_tag')->where('tag_id', $tag->id)->count();

        // 保存されたデータが5件あるか確認する
        $this->assertEquals(3, $count);

    }
}
