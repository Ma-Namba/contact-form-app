<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/admin', function () {
    return redirect()->route('login');
});

//Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/contacts', [ContactController::class, 'thanks'])->name('contact.thanks');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/contacts/{contact}',[AdminController::class,'show'])->name('admin.show');

    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::post('/admin/tags', [TagController::class, 'store'])->name('tag.store');
    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])->name('tag.update');

    Route::delete('/admin/contacts/{contact}',[AdminController::class,'destroy'])->name('contact.delete');
});

// CSV出力(お問い合わせ一覧)
Route::get('contacts/export', [ContactController::class, 'export']);
Route::get('test-csv', [ContactController::class, 'export']);

// JSON(お問い合わせCRUD) - 認証不要

// index(お問い合わせ一覧)
Route::get('/api/v1/contacts', [PublicApiController::class, 'index']);

// show(お問い合わせ詳細)
Route::get('/api/v1/contacts/{contact}', [PublicApiController::class, 'show']);

// store(お問い合わせ新規作成)
Route::post('/api/v1/contacts', [PublicApiController::class, 'store']);

// update(お問い合わせ更新)
Route::put('/api/v1/contacts/{contact}', [PublicApiController::class, 'update']);

// destroy(お問い合わせ削除)
Route::delete('/api/v1/contacts/{contact}', [PublicApiController::class, 'destroy']);
