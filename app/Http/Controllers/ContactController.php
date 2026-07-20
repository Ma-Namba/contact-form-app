<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('contact.index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $category_id = $request->input('category_id');
        $category = Category::find($category_id);
        $validated = $request->validated();
        return view('contact.confirm', compact('validated', 'category'));
    }

    public function thanks(ContactRequest $request)
    {
        $validated = $request->validated();
        Contact::create($validated);
        return view('contact.thanks',compact('validated'));
    }
}
