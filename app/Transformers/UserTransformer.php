<?php


namespace App\Transformers;


use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phohe,
            'avatar' => $user->avatar,
            'openid' => $user->openid,
            'avatar_url' => $user->avatar ? oss_url($user->avatar) : asset('imgs/avatar.png'),
            'is_locked' => $user->is_locked,
            'created_at' => empty($user->created_at) ? $user->created_at : $user->created_at->toDateTimeString(),
            'updated_at' => empty($user->updated_at) ? $user->updated_at : $user->updated_at->toDateTimeString(),
        ];
    }
}
