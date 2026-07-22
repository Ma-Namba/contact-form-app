<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 正常値データのタグがテーブルに追加できること(): void
    {
        $user = User::factory()->create();
        $tag_name = ['name'=>'正常値'];
        $response = $this->actingAs($user)->post(route('tag.store',$tag_name));

        // 管理者画面にリダイレクトを確認
        $response->assertStatus(302);

        $response = $this->assertDatabaseHas(Tag::class, [
            'name' => '正常値',
        ]);
    }

    /** @test */
    public function 正常値データのタグが編集できること(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create([
            'name'=>'タグ名'
        ]);

        $tag_name = ['name' => '正常値'];

        $response = $this->actingAs($user)->put(route('tag.update', ['tag'=>$tag->id]), $tag_name);

        // 管理者画面にリダイレクトを確認
        $response->assertStatus(302);

        $response = $this->assertDatabaseHas(Tag::class, [
            'name' => '正常値',
        ]);
    }

        /** @test */
    public function タグ編集ボタンを押すとタグ編集画面に遷移すること(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create([
            'name' => 'タグ名'
        ]);

        $response = $this->actingAs($user)->get(route('tag.edit', ['tag' => $tag->id]));
        // 正常に画面が遷移することを確認
        $response->assertStatus(200)->assertViewIs('admin.tags.edit');
    }

}
