<?php
// UserController
Route::get('/my-account', 'User\UserController@index');
Route::post('/my-account', 'User\UserController@index');

Route::get('/admins', 'User\UserController@adminManagement');
Route::post('/admins', 'User\UserController@adminManagement');
Route::get('/change-role', 'User\UserController@roleManagement');
Route::post('/change-role', 'User\UserController@roleManagement');

Route::get('/co-admins', 'User\UserController@co_adminManagement');
Route::post('/co-admins', 'User\UserController@co_adminManagement');
Route::get('/create-co-admin', 'User\UserController@createCoAdmin');
Route::get('/edit-co-admin/{id}', 'User\UserController@editCoAdmin');

Route::get('/owners', 'User\UserController@ownerManagement');
Route::post('/owners', 'User\UserController@ownerManagement');

Route::get('/drivers', 'User\UserController@driverManagement');
Route::post('/drivers', 'User\UserController@driverManagement');

Route::get('/users', 'User\UserController@guestManagement');
Route::post('/users', 'User\UserController@guestManagement');

Route::get('/commission', 'User\UserController@commissionManagement');
Route::post('/commission', 'User\UserController@commissionManagement');
Route::get('/countries', 'User\UserController@countriesManagement');
Route::post('/countries', 'User\UserController@countriesManagement');

// MainController
Route::get('/categories', 'User\MainController@categoryManagement');
Route::post('/categories', 'User\MainController@categoryManagement');
Route::get('/restaurants', 'User\MainController@restaurantManagement');
Route::post('/restaurants', 'User\MainController@restaurantManagement');
Route::get('/restaurant-edit/{id}', 'User\MainController@editRestaurant');
Route::post('/restaurant-edit/{id}', 'User\MainController@editRestaurant');

Route::get('/menus', 'User\MainController@restaurantMenus');
Route::get('/menus/{r_id}', 'User\MainController@manageMenus');
Route::post('/menus/{r_id}', 'User\MainController@manageMenus');
Route::get('/menus/{r_id}/{m_id}', 'User\MainController@manageFoods');
Route::post('/menus/{r_id}/{m_id}', 'User\MainController@manageFoods');

Route::get('/extras', 'User\MainController@manageExtras');
Route::post('/extras', 'User\MainController@manageExtras');

Route::get('/messages', 'User\MainController@manageMessages');
Route::post('/messages', 'User\MainController@manageMessages');

Route::get('/message-to-owners', 'User\MainController@manageMessageOwners');
Route::post('/message-to-owners', 'User\MainController@manageMessageOwners');

Route::get('/message-to-users', 'User\MainController@manageMessageUsers');
Route::post('/message-to-users', 'User\MainController@manageMessageUsers');

Route::get('/orders', 'User\MainController@manageOrders');
Route::post('/orders', 'User\MainController@manageOrders');
Route::post('/get-order-details', 'User\MainController@getOrderDetails');
Route::post('/complete-order', 'User\MainController@completeOrder');

Route::get('/reports/{report_type}', 'User\MainController@reportsManagement');