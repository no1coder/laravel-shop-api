<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmailCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required|email'
        ], [
            'code.required' => '验证码 不能为空'
        ]);

        // 验证code是否正确
        if (cache('email_code_' . $request->input('email')) != $request->input('code')) {
            abort(400, '验证码或邮箱错误');
        }

        return $next($request);
    }
}
