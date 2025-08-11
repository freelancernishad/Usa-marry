<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\Coupon\CouponController;
use App\Http\Controllers\Api\Admin\Users\UserController;
use App\Http\Controllers\Api\Auth\Admin\AdminAuthController;
use App\Http\Controllers\UsaMarry\Api\User\Auth\AuthController;
use App\Http\Controllers\UsaMarry\Api\Admin\Plans\PlanController;
use App\Http\Controllers\UsaMarry\Api\Admin\Plans\FeatureController;
use App\Http\Controllers\Api\Admin\Blogs\Articles\ArticlesController;
use App\Http\Controllers\Api\Admin\Blogs\Category\CategoryController;
use App\Http\Controllers\Api\Admin\Transitions\AdminPaymentController;
use App\Http\Controllers\UsaMarry\Api\Admin\UserManagement\BlockController;
use App\Http\Controllers\UsaMarry\Api\Admin\UserManagement\ReportController;
use App\Http\Controllers\Api\Admin\DashboardMetrics\AdminDashboardController;

Route::prefix('auth/admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('register', [AdminAuthController::class, 'register']);

    Route::middleware(AuthenticateAdmin::class)->group(function () { // Applying admin middleware
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('/change-password', [AdminAuthController::class, 'changePassword']);
        Route::get('check-token', [AdminAuthController::class, 'checkToken']);

    });
});


Route::prefix('admin')->group(function () {

    Route::middleware(AuthenticateAdmin::class)->group(function () {

        Route::post('/login-user-by-email', [AuthController::class, 'adminLoginUserByEmail']);



        Route::get('/dashboard-overview', [AdminDashboardController::class, 'adminDashboardOverview']);



        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index']); // list users
            Route::get('/{id}', [UserController::class, 'show']); // single user details
            Route::get('/{id}/subscription', [UserController::class, 'showSubscription']); // user subscription



            Route::post('/{id}/ban', [UserController::class, 'ban']);
            Route::post('/{id}/unban', [UserController::class, 'unban']);
            Route::delete('/{id}', [UserController::class, 'destroy']);

            // For API route
            Route::post('/{id}/toggle-top-profile', [UserController::class, 'toggleTopProfile']);
            Route::get('/top/profiles', [UserController::class, 'topProfiles']);




        });




            // রিপোর্ট লিস্ট
            Route::get('reports', [ReportController::class, 'index']);
            Route::get('reports/user/{userId}', [ReportController::class, 'reportsByUser']);

            // ব্লকড ইউজার লিস্ট
            Route::get('blocks', [BlockController::class, 'index']);
            // আনব্লক only
            Route::delete('blocks/{id}', [BlockController::class, 'destroy']);






        Route::prefix('plan/features')->group(function () {
            Route::get('/', [FeatureController::class, 'index']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::get('{id}', [FeatureController::class, 'show']);
            Route::put('{id}', [FeatureController::class, 'update']);
            Route::delete('{id}', [FeatureController::class, 'destroy']);
            Route::get('/template/list', [FeatureController::class, 'templateInputList']);

        });


        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/', [CouponController::class, 'store']);
            Route::post('/{id}', [CouponController::class, 'update']);
            Route::delete('/{id}', [CouponController::class, 'destroy']);
        });



        Route::prefix('plans')->group(function () {
            Route::get('/', [PlanController::class, 'index']);  // List all plans
            Route::get('{id}', [PlanController::class, 'show']); // Get single plan by ID
            Route::post('/', [PlanController::class, 'store']);  // Create new plan
            Route::put('{id}', [PlanController::class, 'update']); // Update existing plan
            Route::delete('{id}', [PlanController::class, 'destroy']); // Delete a plan
        });




        // Admin routes for blog categories
        Route::group(['prefix' => 'blogs/categories',], function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
            Route::get('/all/list', [CategoryController::class, 'list']);
            Route::put('/reassign-update/{id}', [CategoryController::class, 'reassignAndUpdateParent']);
        });



        Route::prefix('blogs/articles')->group(function () {
            Route::get('/', [ArticlesController::class, 'index']);
            Route::post('/', [ArticlesController::class, 'store']);
            Route::get('{id}', [ArticlesController::class, 'show']);
            Route::post('{id}', [ArticlesController::class, 'update']);
            Route::delete('{id}', [ArticlesController::class, 'destroy']);

            // Add or remove categories to/from articles
            Route::post('{id}/add-category', [ArticlesController::class, 'addCategory']);
            Route::post('{id}/remove-category', [ArticlesController::class, 'removeCategory']);

            Route::get('/by-category/with-child-articles', [ArticlesController::class, 'getArticlesByCategory']);

        });

            Route::get('/transaction-history', [AdminPaymentController::class, 'allSubscriptionHistory']);



    });
});
