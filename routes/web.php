<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('changelocale', ['as' => 'changelocale', 'uses' => 'Common\LocaleController@changeLocale']);
Route::get('/', 'Home\HomeController@index');

// Authentication Routes...
Route::get('/login', function () {
    return redirect('/');
});
Route::post('/login', [ 'as' => 'login', 'uses' => 'Auth\CustomAuthController@loginPost']);
Route::post('/register', [ 'as' => 'register', 'uses' => 'Auth\CustomAuthController@registerPost']);

$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Search routes
Route::get('/search/{location}', 'Home\HomeController@search');
Route::get('/restaurants/{restaurant_id}', 'Home\HomeController@restaurant');
Route::post('/get-food-extras', 'Home\HomeController@getFoodExtras');

// Orders
Route::get('/order-make/{restaurant_id}', 'Home\HomeController@orders');
//Route::post('/order-make', 'Home\HomeController@orders');
Route::post('/order-payment', 'Common\PaymentController@paymentMethods');
Route::get('/payment-status', 'Common\PaymentController@paymentStatus');
Route::get('/payment-success', 'Common\PaymentController@paymentSuccess');
Route::get('/payment-cancel', 'Common\PaymentController@paymentCancel');

Route::get('/about-us', 'Home\HomeController@aboutUs');
Route::get('/contact-us', 'Home\HomeController@contactUs');
Route::post('/contact-us', 'Home\HomeController@contactUs');
Route::get('/faq', 'Home\HomeController@faq');
Route::get('/terms-conditions', 'Home\HomeController@terms');

Route::get('/mail-templates', 'Home\HomeController@templates');

Route::prefix('user')->group(base_path('routes/user_routes.php'));

Route::get('/404', function () {
    return view('404');
});
