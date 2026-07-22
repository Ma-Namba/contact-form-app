<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\IndexContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\StoreContactRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ContactResource;
use Request;


class PublicApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // お問い合わせ一覧（検索・ページネーション付き）のJSON取得
    public function index(IndexContactRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $query = Contact::query();
        $keyword = $validated['keyword'] ?? '';
        $date = $validated['date'] ?? '';
        $gender = $validated['gender'] ?? '';
        $category = $validated['category_id'] ?? '';

        // キーワードで検索 (orWhereが他の条件を壊さないように、where内でグループ化するのが安全です)
        $query->when($keyword, function ($query, $keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', '%' . $keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        });

        // 日付で検索
        $query->when($date, function ($query, $date) {
            return $query->whereDate('created_at', $date); // likeよりwhereDateの方が正確です
        });

        // 性別で検索
        $query->when($gender, function ($query, $gender) {
            return $query->where('gender', $gender); // 完全一致ならlikeは不要です
        });

        // カテゴリーで検索
        $query->when($category, function ($query, $category) {
            return $query->where('category_id', $category);
        });
        // コレクションの件数を数える（DBへの再クエリを防ぐ）
        $per_page = $request->input('per_page',7);

        // データの取得
        $contacts = $query->orderBy('created_at', 'desc')->paginate($per_page);

        // カテゴリ一覧が必要ならここで取得（レスポンスに含める場合）
        $categories = Category::all();

        // JSON形式でレスポンス (複数件なので collection() を使用)
        return response()->json([
            'status' => 'success',
            'meta' => '',
            'data' => ContactResource::collection($contacts)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);
        return response()->json([
            'status' => 'success',
            'meta' => '',
            'data' => new ContactResource($contact)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    // お問い合わせ詳細（カテゴリ・タグ含む）の取得
    public function show(string $id)
    {
        $contact = Contact::with('tags')->findOrFail($id);

        return (new ContactResource($contact))
            ->additional([
                'tags' =>$contact->tags,
                'category' =>$contact->category,
            ])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
    {
        $validated = $request->validated();

        $contact = Contact::find($id);
        $contact->update($validated);

        return (new ContactResource($contact))
            ->additional([
                'tags' => $contact->tags,
                'category' => $contact->category,
            ])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return (new ContactResource($contact))
            ->additional([
                'tags' => $contact->tags,
                'category' => $contact->category,
            ])
            ->response()
            ->setStatusCode(204);
    }

}
