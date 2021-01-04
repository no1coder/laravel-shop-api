<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api.throttle', 'limit' => 60, 'expires' => 1], function ($api) {
    // 路由组
    $api->group(['prefix' => 'auth'], function ($api) {
        // 注册
        $api->post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
        // 登录
        $api->post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

        // 通过邮箱获取验证码
        $api->post('reset/password/email/code', [\App\Http\Controllers\Auth\PasswordResetController::class, 'emailCode']);
        // 执行密码重置
        $api->post('reset/password/email', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPasswordByEmail']);

        // 需要登录的路由
        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 退出登录
            $api->post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);
            // 刷新token
            $api->post('refresh', [\App\Http\Controllers\Auth\LoginController::class, 'refresh']);
            // 阿里云OSS Token
            $api->get('oss/token', [\App\Http\Controllers\Auth\OssController::class, 'token']);
            // 修改密码
            $api->post('password/update', [\App\Http\Controllers\Auth\PasswordController::class, 'updatePassword']);
            // 发送邮件验证码
            $api->post('email/code', [\App\Http\Controllers\Auth\BindController::class, 'emailCode']);
            // 更新邮箱
            $api->patch('email/update', [\App\Http\Controllers\Auth\BindController::class, 'updateEmail']);
            // 发送手机验证码
            $api->post('phone/code', [\App\Http\Controllers\Auth\BindController::class, 'phoneCode']);
            // 更新手机
            $api->patch('phone/update', [\App\Http\Controllers\Auth\BindController::class, 'updatePhone']);
        });
    });
});
