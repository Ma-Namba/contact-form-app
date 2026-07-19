<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/contacts/{contact}',[AdminController::class,'show'])->name('admin.show');

    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::post('/admin/tags', [TagController::class, 'store'])->name('tag.store');
    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])->name('tag.update');

    Route::delete('/admin/contacts/{contact}',[AdminController::class,'destroy'])->name('contact.delete');
});
