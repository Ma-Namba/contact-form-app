<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(AdminRequest $request)
    {
        $user = Auth::user();
        $categories = Category::all();
        $tags = Tag::all();

        $validated = $request->validated();

        $query = Contact::query();
        $keyword = $validated['keyword']??'';
        $date = $validated['date']??'';
        $gender = $validated['gender']??'';
        $category = $validated['category_id']??'';

        // キーワードで検索
        $contacts = $query->when($keyword, function ($query, $keyword) {
            return $query->where('first_name', 'like', '%' . $keyword . '%')
                ->orwhere('last_name', 'like', '%' . $keyword . '%')
                ->orwhere('email', 'like', '%' . $keyword . '%');
        });

        // 日付で検索
        $contacts = $query->when($date, function ($query, $date) {
            return $query->where('created_at', 'like', '%' . $date . '%');
        });

        // 性別で検索
        $contacts = $query->when($gender, function ($query, $gender) {
            return $query->where('gender', 'like',$gender);
        });

        // カテゴリーで検索
        $contacts = $query->when($category, function ($query, $category) {
            return $query->where('category_id', $category);
        });

        // ページネーション（1ページに7件）
        $contacts = $query->paginate(7);

        return view('admin.index',compact('user','categories','contacts','tags'));
    }

    public function show($contact_id)
    {
        $contact = Contact::find($contact_id);
        return view('admin.show', compact('contact'));
    }

    public function destroy($contact_id)
    {
        $contact = Contact::find($contact_id);
        $contact->delete();
        return redirect()->route('admin.index');
    }
}
