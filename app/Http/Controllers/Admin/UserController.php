<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
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
     * 用户列表
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $users = User::when($name, function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            })
            ->when($email, function ($query) use ($email) {
                $query->where('email', $email);
            })
            ->when($phone, function ($query) use ($phone) {
                $query->where('phone', $phone);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * 添加用户
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create($request->all());

        return $this->response->created();
    }

    /**
     * 更新用户
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:16',
            'email' => 'required|email',
        ]);

        $user->update($request->all());

        return $this->response->noContent();
    }

    /**
     * 用户详情
     */
    public function show(User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 禁用和启用用户
     */
    public function lock(User $user)
    {
        $user->is_locked = $user->is_locked == 0 ? 1 : 0;
        $user->save();
        return $this->response->noContent();
    }
}
