<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = Category::all();
        $tags = Tag::all();
        $query = Contact::query();

        // 検索条件がある場合（仮入れ）
        if ($request->has('keyword')){
            $query->where('title', 'link', '%', $request->keyword, '%');
        }

        // ページネーション
        $contacts = $query->paginate(7);

        return view('admin.index',compact('user','categories','contacts','tags'));
    }

    public function show($contact_id)
    {
        $contact = Contact::find($contact_id);
        return view('admin.show', compact('contact'));
    }

    public function update($tag_id)
    {
        $tag = Tag::find($tag_id);
        return view('admin.tags.edit', compact('tag'));
    }
}
