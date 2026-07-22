<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\AdminRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function update(ContactRequest $request)
    {
        $validated = $request->validated();
        Contact::create($validated);
        return redirect('/thanks');
    }
    public function thanks(ContactRequest $validated)
    {
        return view('contact.thanks', compact('validated'));
    }

        public function export(AdminRequest $request)
    {
        $categories = Category::all();

        $validated = $request->validated();

        $query = Contact::query();
        $keyword = $validated['keyword'] ?? '';
        $date = $validated['date'] ?? '';
        $gender = $validated['gender'] ?? '';
        $category = $validated['category_id'] ?? '';

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
            return $query->where('gender', 'like', $gender);
        });

        // カテゴリーで検索
        $contacts = $query->when($category, function ($query, $category) {
            return $query->where('category_id', $category);
        });

        $contacts = $query->orderBy('created_at','desc')->get();
        $total = $query->count();

        $fileName = 'contacts_export_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($contacts) {
            // 標準出力（ダウンロードストリーム）を開く
            $stream = fopen('php://output', 'w');

            // Excelで開いたときの文字化けを防ぐ（BOMを追加）
            fwrite($stream, pack('C*', 0xEF, 0xBB, 0xBF));

            // 1行目：ヘッダー（カラム名）の書き込み
            fputcsv($stream, ['id','category','first_name','last_name','gender','email','tel','address','building','detail','created_at']);

            // 2行目以降：データの書き込み
            foreach ($contacts as $contact) {
                $tags_name = $contact->tags->implode('name', '、');
                fputcsv($stream, [
                    $contact->id,
                    $contact->first_name,
                    $contact->last_name,
                    $contact->gender,
                    $contact->email,
                    $contact->tel,
                    $contact->address,
                    $contact->building,
                    $contact->category->content,
                    $contact->detail,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($stream);
        }, 200, $headers);

    }
}
