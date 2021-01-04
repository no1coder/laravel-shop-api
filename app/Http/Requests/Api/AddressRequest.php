<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Models\City;

class AddressRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'city_id' => [
                'required',
                function($attribute, $value, $fail){
                    $city = City::find($value);
                    if (empty($city)) $fail('区域地址不存在');
                    if (!in_array($city->level, [3, 4])) $fail('区域字段 必须是县级或者乡镇');
                }
            ],
            'address' => 'required',
            'phone' => 'required|regex:/^1[3-9]\d{9}$/',
        ];
    }

    /**
     * 提示消息
     */
    public function messages()
    {
        return [
            'name.required' => '收货人 不能为空',
            'city_id.required' => '地区 不能为空',
            'address.required' => '详细收货地址 不能为空',
            'phone.required' => '手机号 不能为空',
            'address.regex' => '手机号 格式不正确',

        ];
    }
}
