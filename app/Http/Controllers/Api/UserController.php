<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * 用户个人信息详情
     */
    public function userInfo()
    {
        return $this->response->item(auth('api')->user(), new UserTransformer());
    }

    /**
     * 更新用户信息
     */
    public function updateUserInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|max:16',
        ]);

        $user = auth('api')->user();
        $user->name = $request->input('name');
        $user->save();
        return $this->response->noContent();
    }

    /**
     * 更新用户头像
     */
    public function updateAvatar(Request $request)
    {

        
        $request->validate([
            'avatar' => 'required',
        ], [
            'avatar.required' => '头像 不能为空'
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar')->getClientOriginalName();
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $path = $request->file('avatar')->store(
            'avatars/'.\Str::uuid().$extension, 'oss'
            );
             $user = auth('api')->user();
            $user->avatar = $path;
            $user->save();
            return $this->response->array(['avatar'=>oss_url($path)]);
        }else{
            $user = auth('api')->user();
            $user->avatar = $request->input('avatar');
            $user->save();
            return $this->response->noContent();  
        }
      
    }
}
