<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\CompareController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PaypalController;
use App\Http\Controllers\user\phonepayController;
use App\Http\Controllers\User\WishListController;
use Illuminate\Support\Facades\Route;

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


// use Illuminate\Support\Facades\Mail;

// Route::get('test', function () {
//     Mail::raw('This is a test email', function ($message) {
//         $message->to('swapnilbadgujar744@gmail.com')
//                 ->subject('Test Email');
//     });
//     return 'Email sent!';
// });

Route::middleware(['guest'])->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('user.register');
        Route::post('/register', 'create')->name('user.make.register');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('user.login');
        Route::post('/login', 'login')->name('user.make.login');
        Route::get('/forgot-password', function () {
            return view('user.auth.forgot-password');
        })->name('password.request');   
        Route::post('forgot-password', 'ForgetPassword')->name('forgot-password');  
        Route::get('/reset-password/{token}', function (string $token) {
            return view('user.auth.reset-password', ['token' => $token]);
        })->name('password.reset');
        Route::post('reset-password', 'ResetPassword')->name('reset-password');  
    });
});



Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('user.home');
    Route::post('/review', 'review')->name('user.review');
    Route::post('/subscribe', 'subscribe')->name('user.subscribe');
    Route::get('/blog', 'blog')->name('user.blog');
    Route::get('/search/blog', 'blog_search')->name('user.search');
    Route::get('/blog/{id}', 'blog_details')->name('user.blog_details');
    Route::get('/blog/category/{id}', 'blog_by_category')->name('user.blog.category');
    Route::get('/product-details/{slug}', 'product_details')->name('user.product_details');
    Route::get('/shop', 'shop')->name('user.shop');
    Route::get('/faq', 'faq_category')->name('user.faq');
    Route::get('/faq/{slug}', 'faq_by_category')->name('user.faqs');
    Route::get('/price/product', 'product_by_price')->name('user.product.price');
    Route::get('/category', 'category')->name('user.category');

    Route::get('/search', 'search_product')->name('user.search.product');
    Route::get('SearchProduct','SearchProduct')->name('SearchProduct');
    Route::get('ProductShow','ProductShow')->name('ProductShow');

    Route::get('/category/product/{slug}', 'product_by_category')->name('user.category.product');
    Route::get('/brand/product/{slug}', 'product_by_brand')->name('user.brand.product');
    Route::get('/brand', 'brands')->name('user.brand');
    Route::get('/shop/category/{id}', 'product_by_category')->name('user.shop.category');
    Route::get('/shop/category/{id}/{cat_id}', 'product_by_sub_category')->name('user.shop.sub.category');
    Route::get('/shop/category/{id}/{cat_id}/{sub_id}', 'product_by_child_category')->name('user.shop.child.category');
    Route::middleware(['auth'])->group(function () {
        Route::get('/add_to_wishlist/{id}', 'add_to_wishlist')->name('user.add_to_wishlist');
        Route::get('/add_to_compare/{id}', 'add_to_compare')->name('user.add_to_compare');
        Route::get('/add_to_cart/{id}', 'add_to_cart')->name('user.add_to_cart');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('user.cart');
        Route::get('/cart/clear', 'clearCart')->name('user.cart.clear');
        Route::post('/cart/update', 'update_cart')->name('user.cart.update');
        Route::get('/cart/remove/{id}', 'removeCartItem')->name('user.cart.remove');
    });
    Route::controller(WishListController::class)->group(function () {
        Route::get('/wishlist', 'index')->name('user.wishlist');
        Route::get('/wishlist/clear', 'clear_wishlist')->name('user.wishlist.clear');
        Route::get('/wishlist/remove/{id}', 'remove_wishlist')->name('user.wishlist.remove');
    });
    Route::controller(CompareController::class)->group(function () {
        Route::get('/compare', 'index')->name('user.compare');
        Route::get('/compare/remove/{id}', 'remove_compare')->name('user.compare.remove');
    });
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('user.checkout');
        Route::post('/billing/address', 'update_billing_address')->name('user.billing_address');
        Route::get('/payment', 'payment')->name('user.payment');
        Route::get('/order', 'order')->name('user.order');
        Route::post('/checkout/cash-on-delivery', 'checkout_submit_cash_on_delivery')->name('user.checkout.cash.on.delivery');
        Route::post('/checkout/bank-transfer', 'checkout_submit_back_transfer')->name('user.checkout.bank.transfer');

        Route::get('order/orderCancel/{id}', 'orderCancel')->name('user.checkout.order.cancel');

        // Route::post('/stripe', 'stripePost')->name('user.checkout.stripe');
        Route::get('/generateInvoice/{userid}', 'generateInvoice')->name('generateInvoice');

        Route::get('/stripe', 'stripe')->name('stripe.show');
        Route::get('/upi', 'upi')->name('upi.pyament');

        Route::post('/stripe', 'stripePost')->name('stripe.post');
        Route::get('generateInvoice/{orderId}','generateInvoice')->name('generateInvoice');
    });
   
    // Route::get('/stripe', [CheckoutController::class,'stripe'])->name('stripe.show');


    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('user.dashboard');
        Route::get('/profile', 'show_profile')->name('user.dashboard.profile');
        Route::post('/update/profile', 'update_profile')->name('user.profile.update');
        Route::get('/address', 'show_address')->name('user.dashboard.address');
        Route::post('/update/address', 'update_address')->name('user.address.update');
        Route::get('/logout', 'logout')->name('user.logout');
    });
});

Route::controller(phonepayController::class)->group( function(){
    Route::get('phonepay','phonepay')->name('phonepay.index')->middleware('throttle:60,1');
    Route::any('phonepay-response','response')->name("response");
});

Route::controller(PaypalController::class)->group( function(){
    Route::post('/paypal', 'paypal')->name('paypal');
    Route::get('/success', 'success')->name('paypal.success');
    Route::get('/cancel', 'cancel')->name('paypal.cancel');
    // Route::any('phonepay-response','response')->name("response");
});


Route::get('/succesful', function () {
   return view('temp');
})->name('temp');

require_once 'admin.php';