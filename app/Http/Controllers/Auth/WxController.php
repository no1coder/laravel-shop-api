<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use GuzzleHttp;
use Illuminate\Http\Request;

class WxController extends BaseController
{
    /**
     * 通过code进行登录
     */
    public function code(Request $request)
    {
        // 验证参数
        $request->validate([
            'appid' => 'required',
            'secret' => 'required',
            'js_code' => 'required',
        ], [
            'appid.required' => 'appid 不能为空',
            'secret.required' => 'secret 不能为空',
            'js_code.required' => 'js_code 不能为空',
        ]);

        // 发送请求获取openid
        $http = new GuzzleHttp\Client;

        $response = $http->get('https://api.weixin.qq.com/sns/jscode2session', [
            'query' => [
                'appid' => $request->appid,
                'secret' => $request->secret,
                'js_code' => $request->js_code,
                'grant_type' => 'authorization_code',
            ],
        ]);

        $resData = json_decode($response->getBody()->getContents(), true);

        // 查询用户是否存在
        if (!isset($resData['errcode'])) {
            $user = User::where('openid', $resData['openid'])->first();
            if (empty($user)) {
                return response()->json([
                    'openid' => $resData['openid'],
                    'access_token' => '',
                    'user' => ''
                ]);
//                return $this->response->errorNotFound('用户不存在');
            }

            $access_token = auth('api')->login($user);

            return response()->json([
                'openid' => $resData['openid'],
                'access_token' => $access_token,
                'user' => $this->response->item($user, new UserTransformer())->original
            ]);
        } else {
            return $this->response->errorBadRequest($resData['errmsg']);
        }
    }

    /**
     * 绑定和取消绑定openid
     */
    public function bind(Request $request)
    {
        // 验证参数
        $request->validate([
            'type' => 'required|in:bind,unbind',
            'openid' => 'required',
        ], [
            'type.in' => 'type 必须是 bind 或者 unbind',
            'openid.required' => 'openid 不能为空',
        ]);

        $user = auth('api')->user();

        if ($request->type == 'bind') {
            // 查询openid是否为空
            if (!empty($user->openid)) return $this->response->errorBadRequest('当前账号已绑定, 请先解绑');
            $user->openid = $request->openid;
            $user->save();
            return $this->response->noContent();
        } else {
            if (empty($user->openid)) return $this->response->errorBadRequest('当前账号未绑定, 不能解绑');
            if ($user->openid != $request->openid) return $this->response->errorBadRequest('已绑定微信和当前微信微信不一致, 不能解绑');
            $user->openid = null;
            $user->save();
            return $this->response->noContent();
        }
    }
}
