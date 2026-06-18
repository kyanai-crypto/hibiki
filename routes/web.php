<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\FishingResultController;
use App\Http\Controllers\Member\ReservationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\BusinessDayController;
use App\Http\Controllers\Admin\ClosedDayController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FishingResultAdminController;
use App\Http\Controllers\LineWebhookController;
use Illuminate\Support\Facades\Route;

// ========== 公開ページ ==========
Route::get('/', [CalendarController::class, 'index'])->name('home');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/data', [CalendarController::class, 'data'])->name('calendar.data');
Route::get('/fishing-results', [FishingResultController::class, 'index'])->name('fishing-results.index');
Route::get('/fishing-results/{fishingResult}', [FishingResultController::class, 'show'])->name('fishing-results.show');
Route::get('/price', fn() => view('public.price'))->name('price');

// ========== 認証 ==========
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // メール認証
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ========== 会員エリア ==========
Route::middleware(['auth', 'verified'])->prefix('member')->name('member.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// 予約（会員 + 管理者が閲覧できるようPolicyで制御）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reservations', ReservationController::class)
        ->only(['index', 'create', 'store', 'show']);
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
});

// ========== 管理者エリア ==========
Route::middleware(['auth', 'master'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 予約管理
    Route::resource('reservations', ReservationAdminController::class)->only(['index', 'show']);
    Route::patch('/reservations/{reservation}/approve', [ReservationAdminController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{reservation}/reject', [ReservationAdminController::class, 'reject'])->name('reservations.reject');
    Route::patch('/reservations/{reservation}/cancel', [ReservationAdminController::class, 'cancel'])->name('reservations.cancel');
    Route::patch('/reservations/{reservation}/complete', [ReservationAdminController::class, 'complete'])->name('reservations.complete');

    // 営業日・定休日管理
    Route::resource('business-days', BusinessDayController::class)->except(['show']);
    Route::resource('closed-days', ClosedDayController::class)->except(['show']);

    // 設定
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');

    // 釣果情報
    Route::resource('fishing-results', FishingResultAdminController::class)->except(['show']);
    Route::post('/fishing-results/{fishingResult}/images', [FishingResultAdminController::class, 'storeImage'])->name('fishing-results.images.store');
    Route::delete('/fishing-results/images/{image}', [FishingResultAdminController::class, 'destroyImage'])->name('fishing-results.images.destroy');
});

// ========== LINE Webhook ==========
Route::post('/webhook/line', [LineWebhookController::class, 'handle'])->name('webhook.line');
