<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use App\Traits\BaseTrait;
use App\User;
use App\Model\Permission;
use App\Model\Country;
use App\Model\Restaurant;
use App\Model\RestaurantDriver;
use App\Model\Commission;

class UserController extends Controller
{
    use BaseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            if ($request['action'] == 'change_password') {
                if (!Hash::check($request['current_password'], Auth::user()->getAuthPassword())) {
                    return response()->json(['status' => 'error', 'message' => __('global.errors.password_wrong')]);
                }
                User::where('id', Auth::user()->id)->update(['password' => Hash::make($request['new_password'])]);
                return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
            } else {
                $user = User::find(Auth::user()->id);
                if (strlen($request['avatar']) > 200) {
                    $avatar = "uploads/avatar/avatar_" . $user->id . ".png";
                    $this->base64ToImage($request['avatar'], $avatar);
                    $user->avatar = "/" . $avatar;
                }
                $user->address = $request['address'];
                $user->postcode = $request['postcode'];
                $user->city = $request['city'];
                $user->floor = $request['floor'];
                $user->phone = $request['phone'];
                $user->company = $request['company'];
                $user->description = $request['description'];
                $user->food_time = $request['food_time'];
                $user->save();
                return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
            }
        } else {
            $sidebar = ['menu' => 'my_account', 'sub_menu' => ''];
            return view('backend.index', compact('sidebar', 'user_role', 'user_permission'));
        }
    }
    public function roleManagement(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            if (Auth::user()->role != 1) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            $user = User::find($request->get('user_id'));
            if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
            $owner_permissions = ['restaurant_edit', 'menu_add', 'menu_edit', 'menu_remove', 'driver_add', 'driver_edit', 'driver_remove'];
            switch ($request->get('user_role')) {
                case 'Admin':
                    $user->role = 2;
                    break;
                case 'Editor':
                    $user->role = 3;
                    break;
                case 'Owner':
                    $user->role = 4;
                    $user->permissions = json_encode($owner_permissions);
                    break;
                case 'Driver':
                    $user->role = 5;
                    $user->permissions = '';
                    break;
                default:
                    $user->role = 6;
                    $user->permissions = '';
                    break;
            }
            $user->save();
            return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
        } else {
            if (Auth::user()->role != 1) return redirect('/404');
            $users = User::where('role', '!=', 1)->get();
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'change_role'];
            return view('backend.user_management.changeRole', compact('sidebar', 'user_role', 'user_permission', 'users'));
        }
    }
    public function commissionManagement(Request $request)
    {
        if ($request->method() == 'POST') {
            if (Auth::user()->role > 1) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            $commission = Commission::query()->first();
            if ($commission) {
                $commission->commission = $request['commission'];
                $commission->save();
            } else {
                $new_commission = new Commission();
                $new_commission->commission = (float)$request['commission'];
                $new_commission->save();
            }
            return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
        } else {
            if (Auth::user()->role > 1) return redirect('/404');
            $commission = Commission::query()->first();
            $sidebar = ['menu' => 'commission', 'sub_menu' => ''];
            return view('backend.commission', compact('sidebar', 'commission'));
        }
    }
    public function countriesManagement(Request $request)
    {
        if ($request->method() == 'POST') {
            if (Auth::user()->role > 1) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            switch ($request->get('action')) {
                case 'add':
                    $country_name = $request->get('country_name');
                    // check country name
                    $checkCountry = Country::where('name', $country_name)->first();
                    if ($checkCountry) return response()->json(['status' => 'error', 'message' => __('global.errors.country_exist')]);
                    $new_country = new Country();
                    $new_country->name = $country_name;
                    $new_country->lang = 'en';
                    $new_country->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.add_country')]);
                case 'edit':
                    $country_name = $request->get('country_name');
                    $origin_name = $request->get('origin_name');
                    // check country name
                    $checkCountry = Country::where('name', $country_name)->first();
                    if ($checkCountry) return response()->json(['status' => 'error', 'message' => __('global.errors.country_exist')]);
                    $country = Country::where('name', $origin_name)->first();
                    if (!$country) return response()->json(['status' => 'error', 'message' => __('global.errors.country_undefined')]);
                    $country->name = $country_name;
                    $country->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.edit_country')]);
                case 'remove':
                    $country_name = $request->get('country_name');
                    $country = Country::where('name', $country_name)->first();
                    if (!$country) return response()->json(['status' => 'error', 'message' => __('global.errors.country_undefined')]);
                    $country->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.remove_country')]);
                default:
                    return response()->json(['status' => 'success', 'message' => 'Action is not undefined']);
            }
        } else {
            if (Auth::user()->role > 1) return redirect('/404');
            $countries = Country::all();
            $sidebar = ['menu' => 'countries', 'sub_menu' => ''];
            return view('backend.countries', compact('sidebar', 'countries'));
        }
    }
    public function adminManagement(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            if (Auth::user()->role != 1) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            switch ($request->get('action')) {
                case 'add':
                    // check repeat email
                    $checkUser = User::where('email', $request->get('email'))->first();
                    if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                    $user = new User();
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = Hash::make($request->get('password'));
                    $user->state = 1;
                    $user->role = 2;
                    $user->avatar = '/uploads/avatar/avatar-default-icon.png';
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_create')]);
                case 'edit':
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->name = $request->get('name');
                    // check repeat email
                    if ($user->email != $request->get('email')) {
                        $checkUser = User::where('email', $request->get('email'))->first();
                        if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                        $user->email = $request->get('email');
                    }
                    if ($request->get('changePassword') == 'true') {
                        $user->password = Hash::make($request->get('password'));
                    }
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
                case 'remove':
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_remove')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (Auth::user()->role != 1) return redirect('/404');
            $users = User::where('role', 2)->get();
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'admin'];
            return view('backend.user_management.admins', compact('sidebar', 'user_role', 'user_permission', 'users'));
        }
    }
    public function co_adminManagement(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            if (!$this->checkPermission('co_admin')) return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
            switch ($request->get('action')) {
                case 'add':
                    // check repeat email
                    $checkUser = User::where('email', $request->get('email'))->first();
                    if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                    $user = new User();
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = Hash::make($request->get('password'));
                    $user->state = 1;
                    $user->role = 3;
                    $user->permissions = json_encode($request->get('permissions'));
                    $user->avatar = '/uploads/avatar/avatar-default-icon.png';
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_create')]);
                case 'edit':
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->name = $request->get('name');
                    // check repeat email
                    if ($user->email != $request->get('email')) {
                        $checkUser = User::where('email', $request->get('email'))->first();
                        if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                        $user->email = $request->get('email');
                    }
                    if ($request->get('changePassword') == 'true') {
                        $user->password = Hash::make($request->get('password'));
                    }
                    $user->permissions = json_encode($request->get('permissions'));
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
                case 'remove':
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_remove')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('admin')) return redirect('/404');
            $users = User::where('role', 3)->get();
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'co_admin'];
            return view('backend.user_management.co_admins', compact('sidebar', 'user_role', 'user_permission', 'users'));
        }
    }
    public function createCoAdmin(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if (!$this->checkPermission('co_admin')) return redirect('/404');
        $permissions = Permission::all();
        $sidebar = ['menu' => 'user_management', 'sub_menu' => 'co_admin'];
        return view('backend.user_management.createCoAdmin', compact('sidebar', 'user_role', 'user_permission', 'permissions'));
    }
    public function editCoAdmin(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if (!$this->checkPermission('co_admin')) return redirect('/404');
        $user_id = $request->route('id');
        $user = User::find($user_id);
        if (!$user) return redirect('/404');
        $permissions = Permission::all();
        $sidebar = ['menu' => 'user_management', 'sub_menu' => 'co_admin'];
        return view('backend.user_management.editCoAdmin', compact('sidebar', 'user_role', 'user_permission', 'permissions', 'user'));
    }
    public function ownerManagement(Request $request) {
        $that = $this;
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        $owner_permissions = ['restaurant_edit', 'menu_add', 'menu_edit', 'menu_remove', 'driver_add', 'driver_edit', 'driver_remove'];
        if ($request->method() == 'POST') {
            switch ($request->get('action')) {
                case 'add':
                    if (!$that->checkPermission('owner_add'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    // check repeat email
                    $checkUser = User::where('email', $request->get('email'))->first();
                    if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                    $user = new User();
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = Hash::make($request->get('password'));
                    $user->state = 1;
                    $user->role = 4;
                    $user->permissions = json_encode($owner_permissions);
                    $user->avatar = '/uploads/avatar/avatar-default-icon.png';
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_create')]);
                case 'edit':
                    if (!$that->checkPermission('owner_edit') && !$that->checkPermission('owner_activity'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->name = $request->get('name');
                    // check repeat email
                    if ($user->email != $request->get('email')) {
                        $checkUser = User::where('email', $request->get('email'))->first();
                        if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                        $user->email = $request->get('email');
                    }
                    if ($request->get('changePassword') == 'true') {
                        $user->password = Hash::make($request->get('password'));
                    }
                    if ($request->get('activation') == "activation") {
                        $user->state = 1;
                    } else $user->state = 2;

                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
                case 'remove':
                    if (!$that->checkPermission('owner_remove'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_remove')]);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('owner_add') && !$this->checkPermission('owner_edit')
                && !$this->checkPermission('owner_remove') && !$this->checkPermission('owner_activity'))
                return redirect('/404');
            $users = User::where('role', 4)->get();
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'owner'];
            return view('backend.user_management.owners', compact('sidebar', 'user_role', 'user_permission', 'users'));
        }
    }
    public function driverManagement(Request $request) {
        $that = $this;
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            switch ($request->get('action')) {
                case 'add':
                    if (!$this->checkPermission('driver_add'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    // check repeat email
                    $checkUser = User::where('email', $request->get('email'))->first();
                    if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                    $user = new User();
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = Hash::make($request->get('password'));
                    $user->state = 1;
                    $user->role = 5;
                    $user->permissions = '';
                    $user->avatar = '/uploads/avatar/avatar-default-icon.png';
                    $user->save();
                    $restaurantDriver = new RestaurantDriver();
                    $restaurantDriver->restaurant_id = $request->get('restaurant_id');
                    $restaurantDriver->driver_id = $user->id;
                    $restaurantDriver->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_create')]);
                case 'edit':
                    if (!$this->checkPermission('driver_edit'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'success', 'message' => __('global.errors.user_id_undefined')]);
                    $user->name = $request->get('name');
                    // check repeat email
                    if ($user->email != $request->get('email')) {
                        $checkUser = User::where('email', $request->get('email'))->first();
                        if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                        $user->email = $request->get('email');
                    }
                    if ($request->get('changePassword') == 'true') {
                        $user->password = Hash::make($request->get('password'));
                    }
                    $user->save();
                    RestaurantDriver::query()->where('driver_id', $user->id)->delete();
                    $restaurantDriver = new RestaurantDriver();
                    $restaurantDriver->restaurant_id = $request->get('restaurant_id');
                    $restaurantDriver->driver_id = $user->id;
                    $restaurantDriver->save();
                    return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
                case 'remove':
                    if (!$this->checkPermission('driver_remove'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'error', 'message' => __('global.errors.user_id_undefined')]);
                    $user->delete();
                    RestaurantDriver::query()->where('driver_id', $user->id)->delete();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_remove')]);
                default:
                    return response()->json(['status' => 'success', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('driver_add') && !$this->checkPermission('driver_edit') && !$this->checkPermission('driver_remove'))
                return redirect('/404');
            $drivers = []; $restaurants = []; $driver_restaurant_ids = []; $driver_restaurant_names = [];
            if (Auth::user()->role < 4) {
                $drivers = User::where('role', 5)->get();
                $restaurants = Restaurant::all();
                for ($k = 0; $k < count($drivers); $k++) {
                    $driverRestaurantId = RestaurantDriver::query()->where('driver_id', $drivers[$k]->id)->first();
                    if (!$driverRestaurantId) {
                        array_push($driver_restaurant_ids, '');
                        array_push($driver_restaurant_names, '');
                    } else {
                        $driverRestaurant = Restaurant::find($driverRestaurantId->restaurant_id);
                        array_push($driver_restaurant_ids, $driverRestaurant->id);
                        array_push($driver_restaurant_names, $driverRestaurant->name);
                    }
                }
            } else if (Auth::user()->role == 4) {
                $restaurants = Restaurant::query()->where('owner_id', Auth::user()->id)->get();
                $restaurant_ids = [];
                for ($i = 0; $i < count($restaurants); $i++) {
                    array_push($restaurant_ids, $restaurants[$i]->id);
                }
                $restaurantDrivers = RestaurantDriver::query()->whereIn('restaurant_id', $restaurant_ids)->get();
                $driver_ids = [];
                for ($j = 0; $j < count($restaurantDrivers); $j++) {
                    if (in_array($restaurantDrivers[$j]->driver_id, $driver_ids)) continue;
                    array_push($driver_ids, $restaurantDrivers[$j]->driver_id);
                    array_push($driver_restaurant_ids, $restaurantDrivers[$j]->restaurant_id);
                    array_push($driver_restaurant_names, Restaurant::find($restaurantDrivers[$j]->restaurant_id)->name);
                }
                $drivers = User::query()->where('role', 5)->whereIn('id', $driver_ids)->get();
            }
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'driver'];
            return view('backend.user_management.drivers', compact('sidebar', 'user_role', 'user_permission',
                'drivers', 'driver_restaurant_ids', 'driver_restaurant_names', 'restaurants'));
        }
    }
    public function guestManagement(Request $request) {
        $user_role = Auth::user()->role;
        $user_permission = json_decode(Auth::user()->permissions);
        if ($request->method() == 'POST') {
            switch ($request->get('action')) {
                case 'add':
                    if (!$this->checkPermission('user_add'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    // check repeat email
                    $checkUser = User::where('email', $request->get('email'))->first();
                    if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                    $user = new User();
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = Hash::make($request->get('password'));
                    $user->state = 1;
                    $user->role = 6;
                    $user->avatar = '/uploads/avatar/avatar-default-icon.png';
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.success.user_create')]);
                case 'edit':
                    if (!$this->checkPermission('user_edit'))
                        return response()->json(['status' => 'error', 'message' => __('global.errors.permission_error')]);
                    $user = User::find($request->get('user_id'));
                    if (!$user) return response()->json(['status' => 'success', 'message' => __('global.errors.user_id_undefined')]);
                    // check repeat email
                    $user->name = $request->get('name');
                    if ($user->email != $request->get('email')) {
                        $checkUser = User::where('email', $request->get('email'))->first();
                        if ($checkUser) return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
                        $user->email = $request->get('email');
                    }
                    if ($request->get('changePassword') == 'true') {
                        $user->password = Hash::make($request->get('password'));
                    }
                    $user->save();
                    return response()->json(['status' => 'success', 'message' => __('global.verify.update')]);
                default:
                    return response()->json(['status' => 'success', 'message' => 'Action is not defined']);
            }
        } else {
            if (!$this->checkPermission('user_add') && !$this->checkPermission('user_edit') && !$this->checkPermission('user_remove'))
                return redirect('/404');
            $users = User::where('role', 6)->get();
            $sidebar = ['menu' => 'user_management', 'sub_menu' => 'user'];
            return view('backend.user_management.guests', compact('sidebar', 'user_role', 'user_permission', 'users'));
        }
    }
}
