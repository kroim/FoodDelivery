<?php

namespace App\Http\Controllers\Home;

use App\Model\Message;
use App\Model\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Traits\BaseTrait;
use App\Model\Restaurant;
use App\Model\CategoryRestaurant;
use App\Model\Category;
use App\Model\Menu;
use App\Model\Food;
use App\Model\Extra;
use App\Model\FoodExtra;
use App\Model\Country;
use App\Model\Favourite;

class HomeController extends Controller
{
    use BaseTrait;
    public function index() {
        $popular_restaurants = Restaurant::query()->where('special', '=', 'popular')->limit(6)->get();
        $popular_categories = array();
        for ($i = 0; $i < count($popular_restaurants); $i++) {
            $restaurantCategories = CategoryRestaurant::select('category_restaurant.restaurant_id', 'categories.name')
                ->where('restaurant_id', '=', $popular_restaurants[$i]->id)
                ->leftJoin('categories', 'categories.id', '=', 'category_restaurant.category_id')
                ->get();
            array_push($popular_categories, $restaurantCategories);
        }
        return view('frontend.index', compact('popular_restaurants', 'popular_categories'));
    }
    public function search(Request $request) {
        $location = $request->route('location');
        $restaurants = array();
        $category_restaurant_ids = array();
        $query_category = $request->query('category');
        if (!$query_category || $query_category == 'all') {
            $query_category = 'all';
            $category_restaurants = CategoryRestaurant::query()->select('restaurant_id')->distinct()->get();
            foreach ($category_restaurants as $category_restaurant) {
                array_push($category_restaurant_ids, $category_restaurant->restaurant_id);
            }
        } else {
            $category_restaurants = CategoryRestaurant::query()->select('restaurant_id')
                ->where('category_id', '=', $query_category)->distinct()->get();
            foreach ($category_restaurants as $category_restaurant) {
                array_push($category_restaurant_ids, $category_restaurant->restaurant_id);
            }
        }
        if ($location == 'all') {
            $restaurants = Restaurant::query()->whereIn('id', $category_restaurant_ids)->get();
        } else if ($location == 'favourites') {
            $restaurants = array();
            if (Auth::check()) {
                $favourites = Favourite::query()->where('user_id', Auth::user()->id)->get();
                $favourite_ids = array();
                for ($i = 0; $i < count($favourites); $i++) {
                    array_push($favourite_ids, $favourites->restaurant_id);
                }
                $restaurants = Restaurant::query()->whereIn('id', $favourite_ids)->get();
            }
        } else {
            $locationArr = explode("_", $location);
            if ($locationArr && count($locationArr) == 3) {
                $location_city = urldecode($locationArr[0]);
                $location_state = urldecode($locationArr[1]);
                $location_country_decoded = urldecode($locationArr[2]);
                $location_country = Country::query()->where('name', '=', $location_country_decoded)->first();
                if ($location_country)
                    $restaurants = Restaurant::query()->whereIn('id', $category_restaurant_ids)
                        ->where([['country_id', '=', $location_country->id], ['state', '=', $location_state], ['city', '=', $location_city]])->get();
            }
        }
        $restaurant_category_ids = array();
        for ($index1 = 0; $index1 < count($restaurants); $index1++) {
            $item_ids = CategoryRestaurant::query()->select('category_restaurant.restaurant_id', 'categories.*')
                ->where('restaurant_id', '=', $restaurants[$index1]->id)
                ->leftJoin('categories', 'categories.id', '=', 'category_restaurant.category_id')
                ->get();
            array_push($restaurant_category_ids, $item_ids);
        }
        $all_categories = Category::all();
        return view('frontend.search', compact('location', 'query_category', 'all_categories', 'restaurants', 'restaurant_category_ids'));
    }
    public function restaurant(Request $request) {
        $restaurant_id = $request->route('restaurant_id');
        $restaurant = Restaurant::find($restaurant_id);
        if (!$restaurant) return redirect('/404');
        $query_menu = $request->query('menu');
        $restaurant_menus = Menu::query()->where('restaurant_id', '=', $restaurant_id)->get();
        $selected_menus = array();
        $menu_ids = array();
        if (!$query_menu || $query_menu == 'all') {
            $query_menu = 'all';
            $selected_menus = $restaurant_menus;
            foreach ($restaurant_menus as $menuItem) {
                array_push($menu_ids, $menuItem->id);
            }
        } else {
            $selected_menus = Menu::query()->where('id', '=', $query_menu)->get();
            array_push($menu_ids, (int)$query_menu);
        }
        $foods = Food::query()->whereIn('menu_id', $menu_ids)->get();
        return view('frontend.restaurant', compact('restaurant','query_menu', 'restaurant_menus', 'selected_menus', 'foods'));
    }
    public function getFoodExtras(Request $request) {
        if (isset($request['get_food_extras'])) {
            $food = Food::find($request['food_id']);
            if (!$food) return response()->json(['status'=>'error', 'message'=>__('global.errors.food_undefined')]);
            $menu = Menu::query()->where('id', $food->menu_id)->first();
            $restaurant = Restaurant::query()->where('id', $menu->restaurant_id)->first();
            if ($restaurant->activation == 2) return response()->json(['status'=>'error', 'extras'=>'Restaurant is inactive']);
            if ($restaurant->holiday_closed_day) return response()->json(['status'=>'error', 'extras'=>$restaurant->holiday_closed_content]);
            if (date("H:i:s") < date($restaurant->service_from) || date('H:i:s') > date($restaurant->service_to))
                return response()->json(['status'=>'error', 'message'=>'Service time is ended']);
            $extras = FoodExtra::query()->where('food_id', '=', $food->id)
                ->select('food_extra.food_id', 'extras.*')
                ->leftJoin('extras', 'extras.id', '=', 'food_extra.extra_id')
                ->get();
            return response()->json(['status'=>'success', 'extras'=>$extras]);
        } else {
            return response()->json(['status'=>'error', 'message'=>'Action is not defined.']);
        }
    }
    public function orders(Request $request) {
        if ($request->method() == 'POST') {
            return response()->json(['status'=>'error', 'message'=>'Action is not defined.']);
        } else {
            $restaurant_id = $request->route('restaurant_id');
            $restaurant = Restaurant::query()->where('id', $restaurant_id)->first();
            if (!$restaurant) return view('404');
            return view('frontend.order_make', compact('restaurant'));
        }
    }
    // About, FAQ, Contact Us, Terms & Conditions
    public function aboutUs(Request $request) {
        if ($request->method() == 'POST') {
            return response()->json(['status'=>'error', 'message'=>'Action is not defined.']);
        } else {
            return view('frontend.about_us');
        }
    }
    public function faq(Request $request) {
        if ($request->method() == 'POST') {
            return response()->json(['status'=>'error', 'message'=>'Action is not defined.']);
        } else {
            return view('frontend.faq');
        }
    }
    public function contactUs(Request $request) {
        if ($request->method() == 'POST') {
            $new_message = new Message();
            $new_message->name = $request['name'];
            $new_message->email = $request['email'];
            $new_message->phone = $request['phone'];
            $new_message->subject = $request['subject'];
            $new_message->content = $request['content'];
            if (Auth::check()) {
                $new_message->sender_id = Auth::user()->id;
                if (Auth::user()->role == 4) $new_message->type = 'owner';
                else $new_message->type = 'user';
            } else {
                $findUser = User::query()->where('email', $request['email']);
                if ($findUser) {
                    $new_message->sender_id = $findUser->id;
                    if ($findUser->role == 4) $new_message->type = 'owner';
                    else $new_message->type = 'user';
                } else {
                    $new_message->sender_id = 0;
                    $new_message->type = 'user';
                }
            }
            $new_message->receiver_id = 0;
            $new_message->status = 'unread';
            $new_message->save();
            return response()->json(['status' => 'success', 'message' => 'Your contact message is sent successfully']);
        } else {
            return view('frontend.contact_us');
        }
    }
    public function terms(Request $request) {
        if ($request->method() == 'POST') {
            return response()->json(['status'=>'error', 'message'=>'Action is not defined.']);
        } else {
            return view('frontend.terms');
        }
    }
    public function templates(Request $request) {
        $order = Order::query()->where('id', 12)->first();
        return view('templates.mail_order', compact('order'));
    }
}
