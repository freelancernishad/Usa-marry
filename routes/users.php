<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Api\Coupon\CouponController;
use App\Http\Controllers\UsaMarry\Api\User\Auth\AuthController;
use App\Http\Controllers\UsaMarry\Api\Admin\Plans\PlanController;
use App\Http\Controllers\UsaMarry\Api\User\Match\MatchController;
use App\Http\Controllers\UsaMarry\Api\User\Photo\PhotoController;
use App\Http\Controllers\Api\Notifications\NotificationController;
use App\Http\Controllers\UsaMarry\Api\User\Search\SearchController;
use App\Http\Controllers\UsaMarry\Api\User\Profile\ContactController;
use App\Http\Controllers\UsaMarry\Api\User\Profile\ProfileController;
use App\Http\Controllers\UsaMarry\Api\User\Auth\RegistrationController;
use App\Http\Controllers\UsaMarry\Api\User\Profile\ProfileVisitController;
use App\Http\Controllers\UsaMarry\Api\User\Settings\AllSettingsController;
use App\Http\Controllers\UsaMarry\Api\User\Subscription\SubscriptionController;
use App\Http\Controllers\UsaMarry\Api\User\PartnerPreference\PartnerPreferenceController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('register/account', [RegistrationController::class, 'accountSignup']);

Route::middleware(AuthenticateUser::class)->group(function () {


    Route::post('register/verify-otp', [RegistrationController::class, 'verifyOtp']);
    Route::post('register/resend-otp', [RegistrationController::class, 'resendOtp']);

    Route::post('register/profile', [RegistrationController::class, 'createProfile']);
    Route::post('register/personal-info', [RegistrationController::class, 'personalInformation']);
    Route::post('register/location', [RegistrationController::class, 'locationDetails']);
    Route::post('register/education-career', [RegistrationController::class, 'educationCareer']);
    Route::post('register/about', [RegistrationController::class, 'aboutMe']);
    Route::get('register/completion', [RegistrationController::class, 'getProfileCompletion']);
});





// Public routes
Route::post('/register', [AuthController::class, 'register']);





Route::post('/login', [AuthController::class, 'login']);
Route::get('/plans', [SubscriptionController::class, 'plans']);
  Route::get('plan/{id}', [PlanController::class, 'show']); // Get single plan by ID

// Authenticated routes
Route::middleware(AuthenticateUser::class)->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/overview', [ProfileController::class, 'profileOverview']);
        Route::get('/recent-activities', [ProfileController::class, 'recentStatsOverview']);
        Route::get('/recent-activities/matches', [MatchController::class, 'getMatchesWithLimit']);
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/basic', [ProfileController::class, 'updateBasicInfo']);
        Route::put('/', [ProfileController::class, 'updateProfile']);
    });
    Route::get('/contacts/{contactId}', [ContactController::class, 'showContact']);
    Route::post('/contacts/{contactId}', [ContactController::class, 'showContact']);
    Route::get('/my/contact/views/list', [ContactController::class, 'myViewedContacts']);

    // routes/api.php
    Route::get('/profile-visitors', [ProfileVisitController::class, 'visitors']);


    // Photo routes
    Route::prefix('photos')->group(function () {
        Route::get('/', [PhotoController::class, 'index']);
        Route::post('/', [PhotoController::class, 'store']);
        Route::put('/{photo}/primary', [PhotoController::class, 'setPrimary']);
        Route::delete('/{photo}', [PhotoController::class, 'destroy']);
    });

    // Partner Preference routes
    Route::prefix('partner-preferences')->group(function () {
        Route::get('/', [PartnerPreferenceController::class, 'show']);
        Route::put('/', [PartnerPreferenceController::class, 'update']);
    });

    // Match routes
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'getMatches']);
        Route::get('/{user}', [MatchController::class, 'showMatch']);
        Route::post('/{user}/interest', [MatchController::class, 'expressInterest']);
        Route::post('/{match}/accept', [MatchController::class, 'acceptMatch']);
        Route::post('/{match}/reject', [MatchController::class, 'rejectMatch']);


        Route::get('/profile/new', [MatchController::class, 'newMatches']);
        Route::get('/profile/history', [MatchController::class, 'matchHistory']);
        Route::get('/profile/today', [MatchController::class, 'todaysMatches']);
        Route::get('/profile/mine', [MatchController::class, 'myMatches']);
        Route::get('/profile/near-me', [MatchController::class, 'nearMe']);
        Route::get('/profile/more', [MatchController::class, 'moreMatches']);


    });

    // Subscription routes
    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'mySubscription']);
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    });

    Route::get('/transaction-history', [SubscriptionController::class, 'subscriptionHistory']);



        // Get notifications for the authenticated user or admin
        Route::get('user/notifications', [NotificationController::class, 'index']);

        // Mark a notification as read
        Route::post('user/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);



        Route::prefix('settings')->group(function () {
            Route::post('/photos', [AllSettingsController::class, 'updatePhotoSettings']);
        });




    // Search routes
});
Route::get('/search', [SearchController::class, 'search']);

Route::prefix('coupons')->group(function () {
    Route::post('/apply', [CouponController::class, 'apply']);
    Route::post('/check', [CouponController::class, 'checkCoupon']);

});
