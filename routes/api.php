<?php

$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware' => [
        'api.throttle',
        'serializer:array', // 减少transformer的包裹层
        'bindings' // 支持路由模型注入
    ],
    'limit' => 60,
    'expires' => 1
];

$api->version('v1', $params, function ($api) {
    // 首页数据
    $api->get('/index', [\App\Http\Controllers\Api\IndexController::class, 'index']);
    // 商品详情
    $api->get('goods/{good}', [\App\Http\Controllers\Api\GoodsController::class, 'show']);
    // 商品列表
    $api->get('goods', [\App\Http\Controllers\Api\GoodsController::class, 'index']);

    /**
     * 回调
     */
    // 支付宝支付成功之后的回调
    $api->any('pay/notify/aliyun', [\App\Http\Controllers\Api\PayController::class, 'notifyAliyun']);
    // 微信支付成功之后的回调
    $api->any('pay/notify/wechat', [\App\Http\Controllers\Api\PayController::class, 'notifyWechat']);

    // 需要登录的路由
    $api->group(['middleware' => 'api.auth'], function ($api) {
        /**
         * 个人中心
         */
        // 用户详情
        $api->get('user', [\App\Http\Controllers\Api\UserController::class, 'userInfo']);
        // 更新用户信息
        $api->put('user', [\App\Http\Controllers\Api\UserController::class, 'updateUserInfo']);
        // 更新头像
        $api->patch('user/avatar', [\App\Http\Controllers\Api\UserController::class, 'updateAvatar']);

        /**
         * 购物车
         */
        // 购物车选中
        $api->patch('carts/checked', [\App\Http\Controllers\Api\CartController::class, 'checked']);
        // 购物车的资源路由
        $api->resource('carts', \App\Http\Controllers\Api\CartController::class, [
            'except' => ['show']
        ]);

        /**
         * 订单
         */
        // 订单预览页
        $api->get('orders/preview', [\App\Http\Controllers\Api\OrderController::class, 'preview']);
        // 提交订单
        $api->post('orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
        // 订单详情
        $api->get('orders/{order}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
        // 订单列表
        $api->get('orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
        // 物流查询
        $api->get('orders/{order}/express', [\App\Http\Controllers\Api\OrderController::class, 'express']);
        // 确认收货
        $api->patch('orders/{order}/confirm', [\App\Http\Controllers\Api\OrderController::class, 'confirm']);
        // 评论商品
        $api->post('orders/{order}/comment', [\App\Http\Controllers\Api\CommentController::class, 'store']);

        /**
         * 支付
         */
        // 获取支付信息
        $api->get('orders/{order}/pay', [\App\Http\Controllers\Api\PayController::class, 'pay']);
        // 支付状态
        $api->get('orders/{order}/status', [\App\Http\Controllers\Api\PayController::class, 'payStatus']);

        /**
         * 地址
         */
        // 省市县数据
        $api->get('city', [\App\Http\Controllers\Api\CityController::class, 'index']);
        // 设置为默认地址
        $api->patch('address/{address}/default', [\App\Http\Controllers\Api\AddressController::class, 'default']);
        // 地址相关的资源路由
        $api->resource('address', \App\Http\Controllers\Api\AddressController::class);
    });
});
