<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//============================================== User =======================================================================================
Route::group(['prefix' =>'user'] , function(){
    Route::post('register', [App\Http\Controllers\User\Auth\RegisterController::class, 'register']);
    Route::post('login', [App\Http\Controllers\User\Auth\LoginController::class, 'login']);

    Route::group(['middleware' => ['api', 'CheckUserToken:api-user']] , function(){
        Route::post('logout', [App\Http\Controllers\User\Auth\LogoutController::class, 'logout']);

        //============================================= User Cart ======================================================
        Route::group(['prefix' =>'cart'] , function(){
        Route::post('create', [App\Http\Controllers\User\Cart\CreateItemController::class, 'create']);
        Route::post('get-items', [App\Http\Controllers\User\Cart\GetCartItemsController::class, 'getCartItems']);
        Route::post('update', [App\Http\Controllers\User\Cart\UpdateCartItemController::class, 'update']);
        Route::post('delete', [App\Http\Controllers\User\Cart\DeleteCartItemController::class, 'delete']);
        Route::post('delete-all', [App\Http\Controllers\User\Cart\DeleteAllItemsController::class, 'deleteAll']);
    });

        //============================================= User Favourite =================================================
        Route::group(['prefix' =>'favourite'] , function(){
        Route::post('create', [App\Http\Controllers\User\Favourite\CreateFavouriteController::class, 'create']);
        Route::post('get-user-favourites', [App\Http\Controllers\User\Favourite\UserFavouriteController::class, 'index']);
        Route::post('delete', [App\Http\Controllers\User\Favourite\DeleteFavouriteController::class, 'delete']);
    });

        //============================================= User Order =====================================================
        Route::group(['prefix' =>'order'] , function(){
        Route::post('create', [App\Http\Controllers\User\Order\CreateOrderController::class, 'create']);
        Route::post('get-user-orders', [App\Http\Controllers\User\Order\GetUserOrdersController::class, 'index']);
        Route::post('read', [App\Http\Controllers\User\Order\ReadOrderController::class, 'show']);
        Route::post('update', [App\Http\Controllers\User\Order\UpdateOrderStatusController::class, 'update']);
        Route::post('delete', [App\Http\Controllers\User\Order\DeleteOrderController::class, 'delete']);
    });

        //============================================= User OrderItem =================================================
        Route::group(['prefix' =>'orderItem'] , function(){
        Route::post('update', [App\Http\Controllers\User\OrderItem\UpdateQuantityController::class, 'updateQuantity']);
        Route::post('delete', [App\Http\Controllers\User\OrderItem\DeleteOrderItemController::class, 'delete']);
    });

        //============================================= User Product ===================================================
        Route::group(['prefix' =>'product'] , function(){
        Route::post('read', [App\Http\Controllers\User\Product\ReadProductController::class, 'show']);    
    });

        //============================================= User Section ===================================================
        Route::group(['prefix' =>'section'] , function(){
        Route::post('get-sections', [App\Http\Controllers\User\Section\GetSectionsController::class, 'index']);
        Route::post('read', [App\Http\Controllers\User\Section\ReadSectionController::class, 'show']);
    });         

        //============================================= User Seller ===================================================
        Route::group(['prefix' =>'seller'] , function(){
        Route::post('get-sellers', [App\Http\Controllers\User\Seller\GetSellersController::class, 'index']);
        Route::post('read', [App\Http\Controllers\User\Seller\ReadSellerController::class, 'show']);
    });         

  });
});

//============================================= Seller ===================================================================================
Route::group(['prefix' =>'seller'] , function(){
    Route::post('register', [App\Http\Controllers\Seller\Auth\RegisterController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'login']);

    Route::group(['middleware' => ['api', 'CheckSellerToken:api-seller']] , function(){
    Route::post('logout', [App\Http\Controllers\Seller\Auth\LogoutController::class, 'logout']);
        //============================================= Seller Color ===================================================
        Route::group(['prefix' =>'color'] , function(){       
        Route::post('create', [App\Http\Controllers\Seller\Color\CreateColorController::class, 'create']);
        Route::post('get-products-color', [App\Http\Controllers\Seller\Color\GetProductColosrController::class, 'index']);
        Route::post('read', [App\Http\Controllers\Seller\Color\ReadColorController::class, 'show']);
        Route::post('update', [App\Http\Controllers\Seller\Color\UpdateColorController::class, 'update']);
        Route::post('delete', [App\Http\Controllers\Seller\Color\DeleteColorController::class, 'delete']);
    });

        //============================================= Seller Order ===================================================
        Route::group(['prefix' =>'order'] , function(){       
        Route::post('get-seller-orders', [App\Http\Controllers\Seller\Order\GetSellerOrdersController::class, 'index']);
        Route::post('read', [App\Http\Controllers\Seller\Order\ReadOrderController::class, 'show']);
        Route::post('update', [App\Http\Controllers\Seller\Order\UpdateOrderController::class, 'update']);
    });

        //============================================= Seller Piece ===================================================
        Route::group(['prefix' =>'piece'] , function(){       
            Route::post('create', [App\Http\Controllers\Seller\Piece\CreatePieceController::class, 'create']);
            Route::post('get-color-pieces', [App\Http\Controllers\Seller\Piece\GetColorPiecesController::class, 'index']);
            Route::post('read', [App\Http\Controllers\Seller\Piece\ReadPieceController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Seller\Piece\UpdatePieceController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Seller\Piece\DeletePieceController::class, 'delete']);
    });

        //============================================= Seller Product =================================================
        Route::group(['prefix' =>'product'] , function(){       
            Route::post('create', [App\Http\Controllers\Seller\Product\CreateProductController::class, 'create']);
            Route::post('get-seller-products', [App\Http\Controllers\Seller\Product\GetSellerProductsController::class, 'index']);
            Route::post('read', [App\Http\Controllers\Seller\Product\ReadProductController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Seller\Product\UpdateProductController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Seller\Product\DeleteProductController::class, 'delete']);
    });
    
   });
});

//============================================= Admin =====================================================
Route::group(['prefix' =>'admin'] , function(){
    Route::post('register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);

    Route::group(['middleware' => ['api', 'CheckAdminToken:api-admin' ]] , function(){
    Route::post('logout', [App\Http\Controllers\Admin\Auth\LogoutController::class, 'logout']);

        //============================================= Admin Color =====================================================
        Route::group(['prefix' =>'color'] , function(){       
            Route::post('read', [App\Http\Controllers\Admin\Color\ReadColorController::class, 'show']);
    });

        //============================================= Admin Order =====================================================
        Route::group(['prefix' =>'order'] , function(){       
            Route::post('get-orders', [App\Http\Controllers\Admin\Order\GetOrdersController::class, 'index']);
            Route::post('read', [App\Http\Controllers\Admin\Order\ReadOrderController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Admin\Order\UpdateOrderController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\User\Order\DeleteOrderController::class, 'delete']);
    });

        //============================================= Admin Piece =====================================================
        Route::group(['prefix' =>'piece'] , function(){       
            Route::post('read', [App\Http\Controllers\Admin\Piece\ReadPieceController::class, 'show']);
    });

        //============================================= Admin Product ===================================================
        Route::group(['prefix' =>'product'] , function(){       
            Route::post('read', [App\Http\Controllers\Admin\Product\ReadProductController::class, 'show']);
            Route::post('recommend', [App\Http\Controllers\Admin\Product\ReadProductController::class, 'showRecommendations']);
            Route::post('update', [App\Http\Controllers\Admin\Product\UpdateProductController::class, 'update']);
    });

        //============================================= Admin Section ===================================================
        Route::group(['prefix' =>'section'] , function(){       
            Route::post('create', [App\Http\Controllers\Admin\Section\CreatSectionController::class, 'create']);
            Route::post('get-sections', [App\Http\Controllers\Admin\Section\GetSectionController::class, 'index']);
            Route::post('read', [App\Http\Controllers\Admin\Section\ReadSectionController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Admin\Section\UpdateSectionController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Admin\Section\DeleteSectionController::class, 'delete']);
    });

        //============================================= Admin Seller ====================================================
        Route::group(['prefix' =>'seller'] , function(){       
            Route::post('get-sellers', [App\Http\Controllers\Admin\Seller\GetSellersController::class, 'index']);
            Route::post('read', [App\Http\Controllers\Admin\Seller\ReadSellerController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Admin\Seller\UpdateSellerStatusController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Admin\Seller\DeleteUserController::class, 'delete']);
    });

        //============================================= Admin User ======================================================
        Route::group(['prefix' =>'user'] , function(){       
            Route::post('get-users', [App\Http\Controllers\Admin\User\GetUsersControllers::class, 'index']);
            Route::post('read', [App\Http\Controllers\Admin\User\ReadUserController::class, 'show']);
            Route::post('update', [App\Http\Controllers\Admin\User\UpdateUserStatusController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Admin\User\deleteUserController::class, 'delete']);
    });

        //============================================= Admin CRUD ======================================================
        Route::group(['prefix' =>'crud'] , function(){       
            Route::post('create', [App\Http\Controllers\Admin\CRUD\DynamicCrudController::class, 'create']);
            Route::post('read', [App\Http\Controllers\Admin\CRUD\DynamicCrudController::class, 'read']);
            Route::post('update', [App\Http\Controllers\Admin\CRUD\DynamicCrudController::class, 'update']);
            Route::post('delete', [App\Http\Controllers\Admin\CRUD\DynamicCrudController::class, 'delete']);
        });

        //============================================= Admin payment ======================================================
        Route::group(['prefix' =>'payment'] , function(){       
            Route::post('create', [App\Http\Controllers\Admin\Payment\CreatePaymentController::class, 'create']);
    });

        //============================================= Admin recommendation ======================================================
        Route::group(['prefix' =>'recommendation'] , function(){       
            Route::post('recommendation', [App\Http\Controllers\Admin\Recomms\RecommendationController::class, 'getRecommendations']);
    });

        //============================================= Admin notification ======================================================
        Route::group(['prefix' =>'notification'] , function(){       
            Route::post('notification', [App\Http\Controllers\Admin\Notification\NotificationController::class, 'sendNotification']);
    });
    
  });
});
