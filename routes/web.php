<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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


Route::get('/', [DocumentController::class, 'index'])->name('documents.index');

Route::get('/upload', [DocumentController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [DocumentController::class, 'handleUpload'])->name('upload.handle');

Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
Route::get('/documents/searchAll', [DocumentController::class, 'searchAll'])->name('documents.searchAll');
Route::get('/documents/{id}/highlight', [DocumentController::class, 'highlight'])->name('documents.highlight');

Route::get('/documents/stats', [DocumentController::class, 'stats'])->name('documents.stats');
Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
Route::get('/check-cloudinary', function () {
    try {
        $upload = Cloudinary::upload(public_path('AYA IBRAHIM ABED CV.pdf'), [
            'resource_type' => 'auto',
        ]);
        return $upload->getSecurePath();
    } catch (\Exception $e) {
        return $e->getMessage(); // لمعرفة الخطأ بالضبط
    }
});

