<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class LocaleController extends Controller
{
    //
    public function changeLocale(Request $request) {
        $this->validate($request, ['locale' => 'required|in:de,tr,en']);
        \Session::put('locale', $request['locale']);
        return redirect()->back();
    }
}
