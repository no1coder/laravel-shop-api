<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    /**
     * 后台首页数据
     */
    public function index()
    {
        $users_count = User::count();
        $goods_count = Good::count();
        $order_count = Order::count();
        return $this->response->array([
            'users_count' => $users_count,
            'goods_count' => $goods_count,
            'order_count' => $order_count
        ]);
    }
}
