<?php

$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware' => [
        'api.throttle',
        'serializer:array', // 减少transformer的包裹层
        'bindings' // 支持路由模型注入
    ],
    'limit' => 60,
    'expires' => 1,
];

$api->version('v1', $params, function ($api) {
    $api->group(['prefix' => 'admin'], function ($api){
        // 需要登录的路由
        $api->group(['middleware' => ['api.auth', 'check.permission']], function ($api) {
            /**
             * 首页统计数据
             */
            $api->get('index', [\App\Http\Controllers\Admin\IndexController::class, 'index'])->name('admin.index');

            /**
             * 用户管理
             */
            // 当前登录用户详情
            $api->get('user', [\App\Http\Controllers\Admin\UserController::class, 'userInfo'])->name('user.info');
            // 禁用用户/启用用户
            $api->patch('users/{user}/lock', [\App\Http\Controllers\Admin\UserController::class, 'lock'])->name('users.lock');
            // 用户管理资源路由
            $api->resource('users', \App\Http\Controllers\Admin\UserController::class, [
                'only' => ['index', 'show', 'store', 'update']
            ]);

            /**
             * 分类管理
             */
            // 分类禁用和启用
            $api->patch('category/{category}/status', [\App\Http\Controllers\Admin\CategoryController::class, 'status'])->name('category.status');
            // 分类管理资源路由
            $api->resource('category', \App\Http\Controllers\Admin\CategoryController::class, [
                'except' => ['destroy']
            ]);

            /**
             * 商品管理
             */
            // 是否上架
            $api->patch('goods/{good}/on', [\App\Http\Controllers\Admin\GoodsController::class, 'isOn'])->name('goods.on');
            // 是否推荐
            $api->patch('goods/{good}/recommend', [\App\Http\Controllers\Admin\GoodsController::class, 'isRecommend'])->name('goods.recommend');
            // 商品管理资源路由
            $api->resource('goods', \App\Http\Controllers\Admin\GoodsController::class, [
                'except' => ['destroy']
            ]);

            /**
             * 评价管理
             */
            // 评价列表
            $api->get('comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
            // 评价详情
            $api->get('comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'show'])->name('comments.show');
            // 回复评价
            $api->patch('comments/{comment}/reply', [\App\Http\Controllers\Admin\CommentController::class, 'reply'])->name('comments.reply');

            /**
             * 订单管理
             */
            // 订单列表
            $api->get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('order.index');
            // 订单详情
            $api->get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('order.show');
            // 订单发货
            $api->patch('orders/{order}/post', [\App\Http\Controllers\Admin\OrderController::class, 'post'])->name('order.post');

            /**
             * 轮播图管理
             */
            // 轮播排序
            $api->patch('slides/{slide}/seq', [\App\Http\Controllers\Admin\SlideController::class, 'seq'])->name('slides.seq');
            // 轮播禁用和启用
            $api->patch('slides/{slide}/status', [\App\Http\Controllers\Admin\SlideController::class, 'status'])->name('slides.status');
            // 轮播管理资源路由
            $api->resource('slides', \App\Http\Controllers\Admin\SlideController::class);

            /**
             * 菜单管理
             */
            $api->get('menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('menus.index');
        });

    });
});
