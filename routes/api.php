<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Server\ServerStatusController;
use App\Http\Controllers\Api\User\Package\UserPackageController;
use App\Http\Controllers\UsaMarry\Api\Admin\Plans\PlanController;
use App\Http\Controllers\Api\Admin\Blogs\Articles\ArticlesController;
use App\Http\Controllers\Api\Admin\Blogs\Category\CategoryController;
use App\Http\Controllers\Api\User\UserManagement\UserProfileController;
use App\Http\Controllers\Api\User\PackageAddon\UserPackageAddonController;

// Load InitialRoutes
if (file_exists($userRoutes = __DIR__.'/InitialRoutes/example.php')) {
    require $userRoutes;
}


if (file_exists($userRoutes = __DIR__.'/InitialRoutes/users.php')) {
    require $userRoutes;
}

if (file_exists($adminRoutes = __DIR__.'/InitialRoutes/admins.php')) {
    require $adminRoutes;
}




// Load users and admins route files

if (file_exists($userRoutes = __DIR__.'/users.php')) {
    require $userRoutes;
}

if (file_exists($adminRoutes = __DIR__.'/admins.php')) {
    require $adminRoutes;
}





if (file_exists($stripeRoutes = __DIR__.'/Gateways/stripe.php')) {
    require $stripeRoutes;
}


if (file_exists($userConnectionRoutes = __DIR__.'/userConnectionRoutes.php')) {
    require $userConnectionRoutes;
}


if (file_exists($PhotoRequestRoutes = __DIR__.'/PhotoRequestRoutes.php')) {
    require $PhotoRequestRoutes;
}



Route::get('/server-status', [ServerStatusController::class, 'checkStatus']);






// Route to get all packages with discounts (query params for discount_months)
Route::get('global/packages', [UserPackageController::class, 'index']);

// Route to get a single package by ID with discounts
Route::get('global/package/{id}', [UserPackageController::class, 'show']);

Route::prefix('global/')->group(function () {
    Route::get('package-addons/', [UserPackageAddonController::class, 'index']); // List all addons
    Route::get('package-addons/{id}', [UserPackageAddonController::class, 'show']); // Get a specific addon
});






        // Admin routes for blog categories
        Route::group(['prefix' => 'blogs/categories',], function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::get('/all/list', [CategoryController::class, 'list']);

        });



        Route::prefix('blogs/articles')->group(function () {
            Route::get('/', [ArticlesController::class, 'index']);
            Route::get('{id}', [ArticlesController::class, 'show']);
            Route::get('/by-category/with-child-articles', [ArticlesController::class, 'getArticlesByCategory']);

        });





        Route::get('/import-users', [UserProfileController::class, 'importUsersFromAPI']);