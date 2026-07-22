<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{

    public function store(Request $request)
    {
        //タグバリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags',
        ]);
        $tag = Tag::create(['name' => $validated['name'],]);

        return redirect()->route('admin.index',$tag);
    }
    public function edit($tag_id)
    {
        $tag = Tag::find($tag_id);
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request,$tag_id)
    {
        //タグバリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags',
        ]);

        $tag = Tag::find($tag_id);
        $tag->update(['name' => $validated['name'],]);

        return redirect()->route('admin.index',$tag);
    }
}
