<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends BaseController
{
    /**
     * 用户注册
     */
    public function store(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        if ($request->input('openid')) $user->openid = $request->input('openid');
        if ($request->input('avatar')) $user->avatar = $request->input('avatar');
        $user->save();
        return $this->response->created();
    }
}
