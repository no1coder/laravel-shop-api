<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    /**
     * 省市县数据
     */
    public function index(Request $request)
    {
        return city_cache($request->query('pid', 0));
    }
}
