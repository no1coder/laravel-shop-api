<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends BaseController
{
    public function __construct()
    {
        $this->middleware('check.email.code')->only('resetPasswordByEmail');
    }

    /**
     * 获取邮件的验证码
     */
    public function emailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        // 发送验证码到邮件
        Mail::to($request->input('email'))->queue(new SendCode($request->input('email')));

        return $this->response->noContent();
    }

    /**
     * 通过邮箱更新密码
     */
    public function resetPasswordByEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'exists:users',
                'password' => 'required|min:6|max:16|confirmed',
            ]
        );

        $user = User::where('email', $request->input('email'))->first();
        
        $user->password = bcrypt($request->input('password'));
        if ($user->id == 1 || $user->id == 2) return $this->response->errorBadRequest('禁止操作系统数据');

        $user->save();
        return $this->response->noContent();
    }
}
