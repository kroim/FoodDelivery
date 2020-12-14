<?php

namespace App\Http\Controllers\User;

use const http\Client\Curl\AUTH_ANY;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Traits\BaseTrait;
use App\User;
use App\Model\Permission;
use App\Model\Country;
use App\Model\Category;
use App\Model\Restaurant;
use App\Model\Menu;
use App\Model\Food;
use App\Model\Extra;
use App\Model\FoodExtra;
use App\Model\CategoryRestaurant;
use App\Model\Order;
use App\Model\Message;
use App\Model\Commission;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    use BaseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function categoryManagement(Request $request)
    {
        if ($request->method() == 'POST') {
            if (!$this->checkPermission('p1')) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            switch ($request->get('action')) {
                case 'add':
                    $category = new Category();
                    $category->name = $request->get('category_name');
                    $category->lang = 'en';
                    $category->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_category')]);
                case 'edit':
                    $category = Category::find($request->get('category_id'));
                    if (!$category) return response()->json(['status' => 'error', 'message' => __('global.errors.category_undefined')]);
                    $category->name = $request->get('category_name');
                    $category->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.edit_category')]);
                case 'remove':
                    $category = Category::find($request->get('category_id'));
                    if (!$category) return response()->json(['status' => 'error', 'message' => __('global.errors.category_undefined')]);
                    $category->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_category')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            $categories = Category::all();
            if (!$this->checkPermission('category')) return redirect('/404');
            $sidebar = ['menu' => 'categories', 'sub_menu' => ''];
            return view('backend.main.categories', compact('sidebar', 'categories'));
        }
    }
    public function restaurantManagement(Request $request)
    {
        if ($request->method() == 'POST') {
            switch ($request->get('action')) {
                case 'add':
                    if (!$this->checkPermission('restaurant_add'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $restaurant = new Restaurant();
                    $restaurant_image = "uploads/restaurants/restaurant_" . time() . ".png";
                    $this->base64ToImage($request['image'], $restaurant_image);
                    $restaurant->image = "/" . $restaurant_image;
                    $restaurant->name = $request['name'];
                    $restaurant->description = $request['description'];
                    $restaurant->country_id = $request['country_id'];
                    $restaurant->service_from = $request['service_from'];
                    $restaurant->service_to = $request['service_to'];
                    if ($request['holiday_closed_flag'] == 'true') {
                        $restaurant->holiday_closed_flag = 1;
                        $restaurant->holiday_closed_content = $request['holiday_closed_content'];
                    } else {
                        $restaurant->holiday_closed_flag = 0;
                        $restaurant->holiday_closed_content = '';
                    }
                    $restaurant->mini_order = (float)$request['mini_order'];
                    $restaurant->activation = 1;
                    $restaurant->owner_id = $request['owner_id'];
                    $restaurant->special = $request['special'];
                    $address = $request['address'];
                    $city = $request['city'];
                    $state = $request['state'];
                    $location = $address . ' ' . $city . ' ' . $state;
                    $geo_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBmofwXO4eBxqJY_GxcWJqoVtUnb4GtQAs&address=' . urlencode($location) . '&sensor=false';
                    Log::info($geo_url);
                    $geo = file_get_contents($geo_url);
                    Log::info($geo);
                    $geo = json_decode($geo, true);
                    if ($geo['status'] == 'OK') {
                        // Get Lat & Long
                        $restaurant->address = $address;
                        $restaurant->city = $city;
                        $restaurant->state = $state;
                        $restaurant->lat = $geo['results'][0]['geometry']['location']['lat'];
                        $restaurant->long = $geo['results'][0]['geometry']['location']['lng'];
                    } else {
                        return response()->json(['status' => 'error', 'message' => __('global.errors.address_invalid')]);
                    }
                    $restaurant->save();
                    if ($request['category_ids'] && count($request['category_ids']) > 0) {
                        for ($i = 0; $i < count($request['category_ids']); $i++) {
                            $category_restaurant = new CategoryRestaurant();
                            $category_restaurant->restaurant_id = $restaurant->id;
                            $category_restaurant->category_id = $request['category_ids'][$i];
                            $category_restaurant->save();
                        }
                    }
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_restaurant')]);
                case 'remove':
                    if (!$this->checkPermission('restaurant_remove'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $restaurant = Restaurant::find($request['restaurant_id']);
                    if (!$restaurant) return response()->json(['status' => 'error', 'message' => __('global.errors.restaurant_undefined')]);
                    $restaurant->delete();
                    CategoryRestaurant::query()->where('restaurant_id', $request['restaurant_id'])->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_restaurant')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('restaurant_add') &&
                !$this->checkPermission('restaurant_edit') &&
                !$this->checkPermission('restaurant_remove')) return redirect('/404');
            $restaurants = [];
            if (Auth::user()->role < 4) $restaurants = Restaurant::all();
            else $restaurants = Restaurant::query()->where('owner_id', Auth::user()->id)->get();
            $restaurantCategories = CategoryRestaurant::select('category_restaurant.restaurant_id', 'categories.*')
                ->leftJoin('categories', 'categories.id', '=', 'category_restaurant.category_id')
                ->get();
            $categories = Category::all();
            $countries = Country::all();
            $owners = User::query()->where('role', 4)->get();
            $sidebar = ['menu' => 'restaurants', 'sub_menu' => ''];
            return view('backend.main.restaurants', compact('sidebar', 'restaurants',
                'restaurantCategories', 'categories', 'countries', 'owners'));
        }
    }
    public function editRestaurant(Request $request)
    {
        $restaurant_id = $request->route('id');
        $restaurant = Restaurant::find($restaurant_id);
        if ($request->method() == 'POST') {
            if (!$restaurant) return response()->json(['status' => 'error', 'message' => __('global.errors.restaurant_undefined')]);
            if (!$this->checkPermission('restaurant_edit'))
                return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            $restaurant_image = ltrim($restaurant->image, '/');
            if (strlen($request['image']) > 200) {
                $this->base64ToImage($request['image'], $restaurant_image);
                $restaurant->image = "/" . $restaurant_image;
            }
            $restaurant->name = $request['name'];
            $restaurant->description = $request['description'];
            $restaurant->country_id = $request['country_id'];
            $restaurant->service_from = $request['service_from'];
            $restaurant->service_to = $request['service_to'];
            if ($request['holiday_closed_flag'] == 'true') {
                $restaurant->holiday_closed_flag = 1;
                $restaurant->holiday_closed_content = $request['holiday_closed_content'];
            } else {
                $restaurant->holiday_closed_flag = 0;
                $restaurant->holiday_closed_content = '';
            }
            $restaurant->mini_order = (float)$request['mini_order'];
            $restaurant->owner_id = $request['owner_id'];
            $restaurant->special = $request['special'];
            $address = $request['address'];
            $city = $request['city'];
            $state = $request['state'];
            $location = $address . ' ' . $city . ' ' . $state;
            $geo_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBmofwXO4eBxqJY_GxcWJqoVtUnb4GtQAs&address=' . urlencode($location) . '&sensor=false';
            $geo = file_get_contents($geo_url);
            $geo = json_decode($geo, true);
            if ($geo['status'] == 'OK') {
                // Get Lat & Long
                $restaurant->address = $address;
                $restaurant->city = $city;
                $restaurant->state = $state;
                $restaurant->lat = $geo['results'][0]['geometry']['location']['lat'];
                $restaurant->long = $geo['results'][0]['geometry']['location']['lng'];
            } else {
                return response()->json(['status' => 'error', 'message' => __('global.errors.address_invalid')]);
            }
            $restaurant->save();
            if ($request['category_ids'] && count($request['category_ids']) > 0) {
                CategoryRestaurant::query()->where('restaurant_id', $restaurant->id)->delete();
                for ($i = 0; $i < count($request['category_ids']); $i++) {
                    $category_restaurant = new CategoryRestaurant();
                    $category_restaurant->restaurant_id = $restaurant->id;
                    $category_restaurant->category_id = $request['category_ids'][$i];
                    $category_restaurant->save();
                }
            }
            return response()->json(['status' => 'success', 'message' => __('global.success.edit_restaurant')]);
        } else {
            if (!$restaurant) return redirect('/404');
            if (!$this->checkPermission('restaurant_edit'))
                return redirect('/404');
            $categories = Category::all();
            $restaurantCategories = CategoryRestaurant::query()->where('restaurant_id', '=', $restaurant->id)->get();
            $restaurantCategoryIds = array();
            for ($i = 0; $i < count($restaurantCategories); $i++) {
                array_push($restaurantCategoryIds, $restaurantCategories[$i]->category_id);
            }
            $countries = Country::all();
            $owners = User::query()->where('role', 4)->get();
            $sidebar = ['menu' => 'restaurants', 'sub_menu' => ''];
            return view('backend.main.restaurant_edit', compact('sidebar', 'restaurant',
                'categories', 'restaurantCategoryIds', 'countries', 'owners'));
        }
    }
    public function restaurantMenus()
    {
        if (!$this->checkPermission('menu_add') && !$this->checkPermission('menu_edit') && !$this->checkPermission('menu_remove'))
            return redirect('/404');
        $restaurants = [];
        if (Auth::user()->role < 4) $restaurants = Restaurant::all('id', 'image', 'name');
        else $restaurants = Restaurant::query()->where('owner_id', Auth::user()->id)->get();
        $sidebar = ['menu' => 'menus', 'sub_menu' => ''];
        return view('backend.main.restaurant_menus', compact('sidebar', 'restaurants'));
    }
    public function manageMenus(Request $request)
    {
        $restaurant_id = $request->route('r_id');
        $restaurant = Restaurant::find($restaurant_id, ['id', 'name']);
        if ($request->method() == 'POST') {
            if (!$restaurant) return response()->json(['status' => 'error', 'message' => __('global.errors.restaurant_undefined')]);
            switch ($request['action']) {
                case 'add':
                    if (!$this->checkPermission('menu_add'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $menu = new Menu();
                    $menu_image = "uploads/menus/menu_" . time() . ".png";
                    $this->base64ToImage($request['image'], $menu_image);
                    $menu->image = "/" . $menu_image;
                    $menu->name = $request['name'];
                    $menu->description = $request['description'];
                    $menu->restaurant_id = $restaurant->id;
                    $menu->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_menu')]);
                case 'edit':
                    if (!$this->checkPermission('menu_edit'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $menu = Menu::find($request['menu_id']);
                    if (!$menu) return response()->json(['status' => 'error', 'message' => __('global.errors.menu_undefined')]);
                    if (strlen($request['image']) > 200) {
                        $menu_image = ltrim($menu->image, '/');
                        $this->base64ToImage($request['image'], $menu_image);
                        $menu->image = "/" . $menu_image;
                    }
                    $menu->name = $request['name'];
                    $menu->description = $request['description'];
                    $menu->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.edit_menu')]);
                case 'remove':
                    if (!$this->checkPermission('menu_remove'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $menu = Menu::find($request['menu_id']);
                    if (!$menu) return response()->json(['status' => 'success', 'message' => __('global.errors.menu_undefined')]);
                    $menu->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_menu')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('menu_add') && !$this->checkPermission('menu_edit') && !$this->checkPermission('menu_remove'))
                return redirect('/404');
            if (!$restaurant) return redirect('/404');
            $menus = Menu::where('restaurant_id', $restaurant_id)->get();
            $sidebar = ['menu' => 'menus', 'sub_menu' => ''];
            return view('backend.main.menus', compact('sidebar', 'menus', 'restaurant'));
        }
    }
    public function manageFoods(Request $request)
    {
        if ($request->method() == 'POST') {
            if (!$this->checkPermission('menu_add') && !$this->checkPermission('menu_edit')) return redirect('/404');
            $restaurant_id = $request->route('r_id');
            $menu_id = $request->route('m_id');
            $restaurant = Restaurant::find($restaurant_id);
            $menu = Menu::find($menu_id, ['id', 'name']);
            if (!$restaurant || !$menu) return response()->json(['status' => 'error', 'message' => __('global.errors.menu_undefined')]);
            switch ($request['action']) {
                case 'add':
                    $food = new Food();
                    $food_image = "uploads/foods/food_" . time() . ".png";
                    $this->base64ToImage($request['image'], $food_image);
                    $food->image = "/" . $food_image;
                    $food->name = $request['name'];
                    $food->description = $request['description'];
                    $food->price = $request['price'];
                    $food->menu_id = $menu->id;
                    $food->save();
                    if ($request['extra_ids'] && count($request['extra_ids']) > 0) {
                        for ($i = 0; $i < count($request['extra_ids']); $i++) {
                            $food_extra = new FoodExtra();
                            $food_extra->food_id = $food->id;
                            $food_extra->extra_id = $request['extra_ids'][$i];
                            $food_extra->save();
                        }
                    }
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_food')]);
                case 'edit':
                    $food = Food::find($request['food_id']);
                    if (!$food) return response()->json(['status' => 'error', 'message' => __('global.errors.food_undefined')]);
                    if (strlen($request['image']) > 200) {
                        $food_image = ltrim($food->image, '/');
                        $this->base64ToImage($request['image'], $food_image);
                        $food->image = "/" . $food_image;
                    }
                    $food->name = $request['name'];
                    $food->description = $request['description'];
                    $food->price = $request['price'];
                    $food->menu_id = $menu->id;
                    $food->save();
                    if ($request['extra_ids'] && count($request['extra_ids']) > 0) {
                        FoodExtra::where('food_id', $food->id)->delete();
                        for ($i = 0; $i < count($request['extra_ids']); $i++) {
                            $food_extra = new FoodExtra();
                            $food_extra->food_id = $food->id;
                            $food_extra->extra_id = $request['extra_ids'][$i];
                            $food_extra->save();
                        }
                    }
                    return response()->json(['status' => 'success', 'message' => __('global.success.edit_food')]);
                case 'remove':
                    $food = Food::find($request['food_id']);
                    if (!$food) return response()->json(['status' => 'success', 'message' => __('global.errors.food_undefined')]);
                    $food->delete();
                    FoodExtra::where('food_id', $request['food_id'])->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_food')]);
                case 'get_food':
                    $food = Food::find($request['food_id']);
                    if (!$food) return response()->json(['status' => 'error', 'message' => __('global.errors.food_undefined')]);
                    $extras = FoodExtra::where('food_id', '=', $food->id)->get();
                    $all_extras = Extra::all('id', 'name');
                    return response()->json(['status' => 'success', 'food' => $food, 'extras' => $extras, 'all_extras' => $all_extras]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('menu_add') && !$this->checkPermission('menu_edit')) return redirect('/404');
            $restaurant_id = $request->route('r_id');
            $menu_id = $request->route('m_id');
            $restaurant = Restaurant::find($restaurant_id);
            $menu = Menu::find($menu_id, ['id', 'name']);
            if (!$restaurant || !$menu) return redirect('/404');
            $foods = Food::where('menu_id', $menu_id)->get();
            $extras = Extra::all();
            $sidebar = ['menu' => 'foods', 'sub_menu' => ''];
            return view('backend.main.foods', compact('sidebar', 'restaurant_id', 'menu', 'foods', 'extras'));
        }
    }
    public function manageExtras(Request $request)
    {
        if ($request->method() == 'POST') {
            if (Auth::user()->role > 2) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            switch ($request['action']) {
                case 'add':
                    $extra = new Extra();
                    $extra->name = $request['name'];
                    $extra->price = $request['price'];
                    $extra->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_extra')]);
                case 'edit':
                    $extra = Extra::find($request['extra_id']);
                    if (!$extra) return response()->json(['status' => 'success', 'message' => __('global.errors.extra_undefined')]);
                    $extra->name = $request['name'];
                    $extra->price = $request['price'];
                    $extra->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.edit_extra')]);
                case 'remove':
                    $extra = Extra::find($request['extra_id']);
                    if (!$extra) return response()->json(['status' => 'error', 'message' => __('global.errors.extra_undefined')]);
                    $extra->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_extra')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (Auth::user()->role > 2) return redirect('/404');
            $extras = Extra::all();
            $sidebar = ['menu' => 'extras', 'sub_menu' => ''];
            return view('backend.main.extras', compact('sidebar', 'extras'));
        }
    }
    public function manageMessages(Request $request)
    {
        if ($request->method() == 'POST') {
            switch ($request['action']) {
                case 'add':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'edit':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'remove':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {

            $sidebar = ['menu' => 'messages', 'sub_menu' => ''];
            return view('backend.main.messages', compact('sidebar'));
        }
    }
    public function manageMessageOwners(Request $request)
    {
        if ($request->method() == 'POST') {
            switch ($request['action']) {
                case 'add':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'edit':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'remove':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            $o_messages = Message::query()->where('type', '=', 'owner')->get();
            $sidebar = ['menu' => 'message_owners', 'sub_menu' => ''];
            return view('backend.main.message_owners', compact('sidebar', 'o_messages'));
        }
    }
    public function manageMessageUsers(Request $request)
    {
        if ($request->method() == 'POST') {
            switch ($request['action']) {
                case 'add':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'edit':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                case 'remove':
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            $u_messages = Message::query()->where('type', '=', 'user')->get();
            $sidebar = ['menu' => 'message_users', 'sub_menu' => ''];
            return view('backend.main.message_users', compact('sidebar', 'u_messages'));
        }
    }
    public function manageOrders(Request $request) {
        $orders = array();
        if (Auth::user()->role == 1 || Auth::user()->role ==2) {
            $orders = Order::all();
        } else if (Auth::user()->role == 7) {
            $orders = Order::query()->where('email', Auth::user()->email)->get();
        }
        $sidebar = ['menu' => 'orders', 'sub_menu' => ''];
        return view('backend.main.orders', compact('sidebar', 'orders'));
    }
    public function getOrderDetails(Request $request) {
        if ($request['action'] == 'get_order_details') {
            $order = Order::find($request['order_id']);
            if (!$order) return response()->json(['status' => 'error', 'message' => __('global.errors.order_undefined')]);
            return response()->json(['status' => 'success', 'order' => $order]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
        }
    }
    public function completeOrder(Request $request) {
        if ($request['action'] == 'complete_order') {
            $order = Order::find($request['order_id']);
            if (!$order) return response()->json(['status' => 'error', 'message' => __('global.errors.order_undefined')]);
            $order->payment_status = 'completed';
            $order->save();
            return response()->json(['status' => 'success', 'message' => __('global.success.complete_order')]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
        }
    }
    public function reportsManagement(Request $request) {
        $report_type = $request->route('report_type');
        if (Auth::user()->role <= 2 || Auth::user()->role == 4) {
            if ($report_type == 'orders') {
                // orders report
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');
                $orders = Order::query();
                if($start_date) $orders = $orders->where('orders.created_at', '>=', $start_date);
                if($end_date) $orders = $orders->where('orders.created_at', '<=', $end_date);
                if (Auth::user()->role == 4) {
                    $orders = $orders->where('restaurants.owner_id', Auth::user()->id);
                }
                $orders = $orders->leftJoin('restaurants', 'restaurants.id', '=', 'orders.restaurant_id')
                    ->select('restaurants.name as restaurant_name', 'orders.*')->get();
                $sidebar = ['menu' => 'reports', 'sub_menu' => 'order_reports'];
                return view('backend.main.reports_order', compact('sidebar', 'report_type', 'orders', 'start_date', 'end_date'));
            } else if ($report_type == 'restaurants') {
                // restaurants report
                $restaurants = Restaurant::query();
                if (Auth::user()->role == 4) $restaurants = $restaurants->where('restaurants.owner_id', Auth::user()->id);
                $restaurants = $restaurants->leftJoin('users', 'users.id', '=', 'restaurants.owner_id')
                    ->select('restaurants.*', 'users.email as email')
                    ->get();
                $orders = array();
                $total_price = 0;
                foreach ($restaurants as $restaurant) {
                    $order_items = Order::query()->where('restaurant_id', $restaurant->id)->get();
                    if (!$order_items || count($order_items) == 0) continue;
                    $items_sum = 0;
                    $items_order = new \stdClass();
                    foreach ($order_items as $index => $order_item) {
                        if ($index == 0) {
                            $items_order->id = $restaurant->id;
                            $items_order->name = $restaurant->name;
                            $items_order->email = $restaurant->email;
                            $items_order->order = count($order_items);
                        }
                        $items_sum += (float)$order_item->order_price;
                    }
                    $items_order->orders_sum = $items_sum;
                    array_push($orders, $items_order);
                    $total_price += $items_sum;
                }
                $sidebar = ['menu' => 'reports', 'sub_menu' => 'restaurant_reports'];
                return view('backend.main.reports_restaurant', compact('sidebar', 'report_type', 'orders', 'total_price'));
            } else if ($report_type == 'customers') {
                // customers report
                $restaurant_ids = array();
                $restaurants = Restaurant::query();
                if (Auth::user()->role == 4) $restaurants = $restaurants->where('owner_id', Auth::user()->id);
                $restaurants = $restaurants->get();
                foreach ($restaurants as $restaurant) {
                    array_push($restaurant_ids, $restaurant->id);
                }
                $orders = Order::query()->whereIn('restaurant_id', $restaurant_ids)->get();
                $customers = array();
                $customers_email = array();
                foreach ($orders as $order) {
                    if (in_array($order->email, $customers_email)) {
                        $email_index = array_search($order->email, $customers_email);
                        $customers[$email_index]->purchased += (float)$order->order_price;
                        $customers[$email_index]->count += 1;
                    } else {
                        array_push($customers_email, $order->email);
                        $customer = new \stdClass();
                        $customer->email = $order->email;
                        $customer->phone = $order->phone;
                        $customer->count = 1;
                        $customer->purchased = (float)$order->order_price;
                        array_push($customers, $customer);
                    }
                }
                $sidebar = ['menu' => 'reports', 'sub_menu' => 'customer_reports'];
                return view('backend.main.reports_customer', compact('sidebar', 'report_type', 'customers'));
            } else {
                return redirect('/404');
            }
        } else {
            return redirect('/404');
        }
    }
}
