<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PresignUrlController;
use App\Http\Controllers\Admin\SeminarController;
use App\Http\Controllers\User\SeminarController as UserSeminarController;

// login for member
Route::post('/login', [AuthController::class, 'login'])->name('member.login');

Route::get('/refresh', [AuthController::class, 'refresh'])->name('refresh')
    ->middleware('auth.user');

//login for admin 
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');

Route::get('/admin/refresh', [AuthController::class, 'refresh'])->name('refresh')
    ->middleware('auth.admin');

Route::middleware('auth.admin:1')->group(function () {
    Route::get('admin/seminars', [SeminarController::class, 'index'])->name('seminar.index');
    Route::post('admin/seminar/create', [SeminarController::class, 'store'])->name('seminar.index');
    Route::post('admin/seminar/create/presignUrl', [PresignUrlController::class, 'getPresignUrl']);
    Route::patch('admin/seminar/delete', [SeminarController::class, 'destroy'])->name('seminar.destroy');
});

Route::post('/seminar-apply', [UserSeminarController::class, 'apply'])->name('seminar.apply')->middleware('auth.user');

// without login can access
Route::middleware('auth.prevent:employee,admin')->group(function () {
    Route::get('/seminars', [UserSeminarController::class, 'index'])->name('Userseminar.index');
    Route::get('/seminar-detail', [UserSeminarController::class, 'show'])->name('Userseminar.show');
});

Route::post('/register', [AuthController::class, 'register'])->name('member.register')->middleware('auth.prevent:employee,admin,user');

Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'errors' => [
            "code" => 400,
            "message" => "Url Not Found: " . request()->getRequestUri()
        ],
        'result' => null
    ], 404);
});
