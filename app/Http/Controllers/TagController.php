<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{

    public function store(Request $request)
    {
        $name = $request->input('name');
        $tag = Tag::create(['name' => $name]);
        return redirect()->route('admin.index');
    }
    public function edit($tag_id)
    {
        $tag = Tag::find($tag_id);
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request,$tag_id)
    {
        $name = $request->input('name');
        $tag = Tag::find($tag_id);
        $tag->update(['name'=>$name]);
        return redirect()->route('admin.index');
    }
}
