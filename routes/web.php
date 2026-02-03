<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DownloadController; // [สำคัญ] อย่าลืมบรรทัดนี้

// Fallback Route
Route::get('/', function () {
    return view('welcome');
});

// API Routes
Route::post('/api/register', [AuthController::class, 'register']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout']);
Route::get('/api/user', [AuthController::class, 'me']);

// News Routes
Route::get('/api/news/archive', [NewsController::class, 'archive']); // Archive ต้องมาก่อน {id}
Route::get('/api/news', [NewsController::class, 'index']);
Route::get('/api/news/{id}', [NewsController::class, 'show']);
Route::post('/api/admin/news', [NewsController::class, 'store']);
Route::put('/api/admin/news/{id}', [NewsController::class, 'update']);
Route::delete('/api/admin/news/{id}', [NewsController::class, 'destroy']);

// -----------------------------------------------------------
// [ส่วนที่ขาดหายไป] Download Routes
// -----------------------------------------------------------
Route::get('/api/downloads', [DownloadController::class, 'index']);
Route::post('/api/admin/downloads', [DownloadController::class, 'store']);
Route::delete('/api/admin/downloads/{id}', [DownloadController::class, 'destroy']);
// -----------------------------------------------------------

// Fallback (ต้องอยู่ล่างสุดเสมอ)
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
