<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ファクトリーからカテゴリーに紐づいたダミーデータ20件投入
        Contact::factory()->count(20)->create();

        //中間データを1つのコンタクトに1〜3件のタグをランダム指定して作成する
        $contactIDs = Contact::pluck('id')->toArray();

        foreach ($contactIDs as $contactID) {
            $contact = Contact::find($contactID);
        $randomLimit = rand(1, 3);
        $random_tags = Tag::inRandomOrder()->limit($randomLimit)->pluck('id')->toArray();
            $contact->tags()->sync($random_tags);
        }
    }
}
