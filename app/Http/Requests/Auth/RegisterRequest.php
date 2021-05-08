<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:16|confirmed',
            'openid' => 'sometimes|required|unique:users,openid'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '昵称 不能为空',
            'name.max' => '昵称 不能超过16个字符',
            'openid.required' => 'openid 不能为空',
            'openid.unique' => 'openid 已绑定其他用户',
        ];
    }
}
