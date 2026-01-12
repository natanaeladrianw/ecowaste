<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\WasteController;
use App\Http\Controllers\User\StatisticsController;
use App\Http\Controllers\User\BankSampahController;
use App\Http\Controllers\User\EducationController;
use App\Http\Controllers\User\PointsController;
use App\Http\Controllers\User\CommunityController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WasteController as AdminWasteController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\WasteTypeController as AdminWasteTypeController;
use App\Http\Controllers\Admin\StatisticsController as AdminStatisticsController;
use App\Http\Controllers\Admin\BankSampahController as AdminBankSampahController;
use App\Http\Controllers\Admin\TipController as AdminTipController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;

// Public Routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('user.dashboard');
    }
    return view('home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// User Routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // Waste Management
    Route::prefix('waste')->name('waste.')->group(function () {
        Route::get('/', [WasteController::class, 'index'])->name('index');
        Route::get('/create', [WasteController::class, 'create'])->name('create');
        Route::post('/', [WasteController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [WasteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WasteController::class, 'update'])->name('update');
        Route::delete('/{id}', [WasteController::class, 'destroy'])->name('destroy');
        Route::get('/history', [WasteController::class, 'history'])->name('history');
    });

    // Statistics
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/daily', [StatisticsController::class, 'daily'])->name('daily');
        Route::get('/weekly', [StatisticsController::class, 'weekly'])->name('weekly');
        Route::get('/monthly', [StatisticsController::class, 'monthly'])->name('monthly');
    });

    // Bank Sampah
    Route::prefix('bank-sampah')->name('bank-sampah.')->group(function () {
        Route::get('/', [BankSampahController::class, 'index'])->name('index');
        Route::get('/map', [BankSampahController::class, 'map'])->name('map');
        Route::get('/{id}', [BankSampahController::class, 'show'])->name('show');
        Route::get('/{id}/route', [BankSampahController::class, 'route'])->name('route');
    });

    // Education
    Route::prefix('education')->name('education.')->group(function () {
        Route::get('/tips', [EducationController::class, 'tips'])->name('tips');
        Route::get('/articles', [EducationController::class, 'articles'])->name('articles');
        Route::get('/challenges', [EducationController::class, 'challenges'])->name('challenges');
        Route::post('/challenges/{id}/complete', [EducationController::class, 'completeChallenge'])->name('challenges.complete');
    });

    // Points & Rewards
    Route::prefix('points')->name('points.')->group(function () {
        Route::get('/', [PointsController::class, 'index'])->name('index');
        Route::get('/rewards', [PointsController::class, 'rewards'])->name('rewards');
        Route::post('/rewards/{id}/claim', [PointsController::class, 'claimReward'])->name('rewards.claim');
    });

    // Community
    Route::prefix('community')->name('community.')->group(function () {
        Route::get('/forum', [CommunityController::class, 'forum'])->name('forum');
        Route::get('/achievements', [CommunityController::class, 'achievements'])->name('achievements');
        Route::post('/achievements/{id}/share', [CommunityController::class, 'shareAchievement'])->name('achievements.share');
        Route::post('/transactions/{id}/share', [CommunityController::class, 'shareTransaction'])->name('transactions.share');
        Route::post('/posts', [CommunityController::class, 'storePost'])->name('posts.store');
        Route::post('/posts/{id}/like', [CommunityController::class, 'likePost'])->name('posts.like');
        Route::post('/posts/{id}/comment', [CommunityController::class, 'storeComment'])->name('posts.comment');
    });
});

// Route aliases untuk views yang menggunakan route tanpa prefix 'user.'
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Waste aliases
    Route::get('/waste', [WasteController::class, 'index'])->name('waste.index');
    Route::get('/waste/create', [WasteController::class, 'create'])->name('waste.create');
    Route::post('/waste', [WasteController::class, 'store'])->name('waste.store');

    // Statistics aliases
    Route::get('/statistics/daily', [StatisticsController::class, 'daily'])->name('statistics.daily');
    Route::get('/statistics/weekly', [StatisticsController::class, 'weekly'])->name('statistics.weekly');
    Route::get('/statistics/monthly', [StatisticsController::class, 'monthly'])->name('statistics.monthly');

    // Bank Sampah aliases
    Route::get('/bank-sampah', [BankSampahController::class, 'index'])->name('bank-sampah.index');
    Route::get('/bank-sampah/map', [BankSampahController::class, 'map'])->name('bank-sampah.map');

    // Education aliases
    Route::get('/education/challenges', [EducationController::class, 'challenges'])->name('education.challenges');
    Route::get('/education/tips', [EducationController::class, 'tips'])->name('education.tips');
    Route::get('/education/articles', [EducationController::class, 'articles'])->name('education.articles');

    // Points aliases
    Route::get('/points', [PointsController::class, 'index'])->name('points.index');
    Route::get('/points/rewards', [PointsController::class, 'rewards'])->name('points.rewards');

    // Community aliases
    Route::get('/community/forum', [CommunityController::class, 'forum'])->name('community.forum');
    Route::get('/community/achievements', [CommunityController::class, 'achievements'])->name('community.achievements');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/activities', [AdminDashboardController::class, 'activities'])->name('activities');

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'index'])->name('index');
            Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        });

        // User Management
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Waste Management
        Route::prefix('waste')->name('waste.')->group(function () {
            Route::get('/reports', [AdminWasteController::class, 'reports'])->name('reports');
            Route::get('/reports/export', [AdminWasteController::class, 'export'])->name('reports.export');
            Route::get('/reports/export-pdf', [AdminWasteController::class, 'exportPdf'])->name('reports.export-pdf');
            Route::post('/{id}/approve', [AdminWasteController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [AdminWasteController::class, 'reject'])->name('reject');
            Route::put('/{id}', [AdminWasteController::class, 'update'])->name('update');
            Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
            Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        });

        // Waste Types Management
        Route::prefix('waste-types')->name('waste-types.')->group(function () {
            Route::get('/', [AdminWasteTypeController::class, 'index'])->name('index');
            Route::get('/create', [AdminWasteTypeController::class, 'create'])->name('create');
            Route::post('/', [AdminWasteTypeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminWasteTypeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminWasteTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminWasteTypeController::class, 'destroy'])->name('destroy');
        });

        // Statistics
        Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('statistics.index');

        // Bank Sampah Management
        Route::resource('bank-sampah', AdminBankSampahController::class);

        // Education Management
        Route::prefix('education')->name('education.')->group(function () {
            Route::get('/tips', [AdminTipController::class, 'index'])->name('tips.index');
            Route::get('/tips/create', [AdminTipController::class, 'create'])->name('tips.create');
            Route::get('/tips/{id}/edit', [AdminTipController::class, 'edit'])->name('tips.edit');
            Route::post('/tips', [AdminTipController::class, 'store'])->name('tips.store');
            Route::put('/tips/{id}', [AdminTipController::class, 'update'])->name('tips.update');
            Route::delete('/tips/{id}', [AdminTipController::class, 'destroy'])->name('tips.destroy');
            Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
            Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
            Route::get('/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
            Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
            Route::put('/articles/{id}', [AdminArticleController::class, 'update'])->name('articles.update');
            Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
            Route::get('/challenges', [AdminChallengeController::class, 'index'])->name('challenges.index');
            Route::get('/challenges/create', [AdminChallengeController::class, 'create'])->name('challenges.create');
            Route::get('/challenges/{id}/edit', [AdminChallengeController::class, 'edit'])->name('challenges.edit');
            Route::post('/challenges', [AdminChallengeController::class, 'store'])->name('challenges.store');
            Route::put('/challenges/{id}', [AdminChallengeController::class, 'update'])->name('challenges.update');
            Route::delete('/challenges/{id}', [AdminChallengeController::class, 'destroy'])->name('challenges.destroy');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::get('/generate', [AdminReportController::class, 'generate'])->name('generate');
            Route::get('/export', [AdminReportController::class, 'export'])->name('export');
        });

        // Rewards Management
        Route::prefix('rewards')->name('rewards.')->group(function () {
            Route::get('/', [AdminRewardController::class, 'index'])->name('index');
            Route::get('/create', [AdminRewardController::class, 'create'])->name('create');
            Route::post('/', [AdminRewardController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminRewardController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminRewardController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminRewardController::class, 'destroy'])->name('destroy');
        });
    });
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');