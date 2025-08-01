<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\AllowedOriginController;
use App\Http\Controllers\Api\Coupon\CouponController;
use App\Http\Controllers\Api\Admin\Users\UserController;
use App\Http\Controllers\Api\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\Package\AdminPackageController;
use App\Http\Controllers\Api\Notifications\NotificationController;
use App\Http\Controllers\Api\SystemSettings\SystemSettingController;
use App\Http\Controllers\Api\Admin\Blogs\Articles\ArticlesController;
use App\Http\Controllers\Api\Admin\Blogs\Category\CategoryController;
use App\Http\Controllers\Api\Auth\Admin\AdminResetPasswordController;
use App\Http\Controllers\Api\Admin\Transitions\AdminPaymentController;
use App\Http\Controllers\Api\Admin\Package\CustomPackageRequestController;
use App\Http\Controllers\Api\Admin\Package\AdminPurchasedHistoryController;
use App\Http\Controllers\Api\Admin\PackageAddon\AdminPackageAddonController;
use App\Http\Controllers\Api\Admin\DashboardMetrics\AdminDashboardController;
use App\Http\Controllers\Api\Admin\SocialMedia\AdminSocialMediaLinkController;
use App\Http\Controllers\Api\Admin\SupportTicket\AdminSupportTicketApiController;


Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () { // Applying admin middleware
        Route::post('/system-setting', [SystemSettingController::class, 'storeOrUpdate']);
        Route::get('/allowed-origins', [AllowedOriginController::class, 'index']);
        Route::post('/allowed-origins', [AllowedOriginController::class, 'store']);
        Route::put('/allowed-origins/{id}', [AllowedOriginController::class, 'update']);
        Route::delete('/allowed-origins/{id}', [AllowedOriginController::class, 'destroy']);




        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index']);





        Route::prefix('transitions')->group(function () {
            Route::get('/transaction-history', [AdminPaymentController::class, 'getAllTransactionHistory'])
                ->name('admin.transitions.transaction-history');
        });


        Route::prefix('social-media')->group(function () {
            Route::get('links', [AdminSocialMediaLinkController::class, 'index'])->name('admin.socialMediaLinks.index');
            Route::get('links/{id}', [AdminSocialMediaLinkController::class, 'show'])->name('admin.socialMediaLinks.show');
            Route::post('links', [AdminSocialMediaLinkController::class, 'store'])->name('admin.socialMediaLinks.store');
            Route::post('links/{id}', [AdminSocialMediaLinkController::class, 'update'])->name('admin.socialMediaLinks.update');
            Route::delete('links/{id}', [AdminSocialMediaLinkController::class, 'destroy'])->name('admin.socialMediaLinks.destroy');
            Route::patch('links/{id}/toggle-status', [AdminSocialMediaLinkController::class, 'toggleStatus']);
            Route::patch('links/{id}/update-index-no', [AdminSocialMediaLinkController::class, 'updateIndexNo']);
        });

        Route::prefix('/')->group(function () {
            Route::get('packages', [AdminPackageController::class, 'index']);
            Route::get('packages/{id}', [AdminPackageController::class, 'show']);
            Route::post('packages', [AdminPackageController::class, 'store']);
            Route::put('packages/{id}', [AdminPackageController::class, 'update']);
            Route::delete('packages/{id}', [AdminPackageController::class, 'destroy']);
        });


        Route::prefix('/')->group(function () {
            Route::get('package-addons/', [AdminPackageAddonController::class, 'index']); // List all addons
            Route::post('package-addons/', [AdminPackageAddonController::class, 'store']); // Create a new addon
            Route::get('package-addons/{id}', [AdminPackageAddonController::class, 'show']); // Get a specific addon
            Route::put('package-addons/{id}', [AdminPackageAddonController::class, 'update']); // Update an addon
            Route::delete('package-addons/{id}', [AdminPackageAddonController::class, 'destroy']); // Delete an addon
        });


        // Support ticket routes
        Route::get('/support', [AdminSupportTicketApiController::class, 'index']);
        Route::get('/support/{ticket}', [AdminSupportTicketApiController::class, 'show']);
        Route::post('/support/{ticket}/reply', [AdminSupportTicketApiController::class, 'reply']);
        Route::patch('/support/{ticket}/status', [AdminSupportTicketApiController::class, 'updateStatus']);




        Route::get('/package/purchased-history', [AdminPurchasedHistoryController::class, 'getAllHistory']);
        Route::get('/package/purchased-history/{id}', [AdminPurchasedHistoryController::class, 'getSingleHistory']);







        // Get notifications for the authenticated user or admin
        Route::get('/notifications', [NotificationController::class, 'index']);

        // Mark a notification as read
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

        // Create a notification for a user (admin only)
        Route::post('/notifications/create-for-user', [NotificationController::class, 'createForUser']);





    // API routes for custom package requests
    Route::prefix('custom/package/requests')->group(function () {
        Route::get('/', [CustomPackageRequestController::class, 'index']); // List all requests
        Route::get('/{id}', [CustomPackageRequestController::class, 'show']); // Show a specific request
        Route::put('/{id}', [CustomPackageRequestController::class, 'update']); // Update a request
        Route::delete('/{id}', [CustomPackageRequestController::class, 'destroy']); // Delete a request
    });





    });
});



