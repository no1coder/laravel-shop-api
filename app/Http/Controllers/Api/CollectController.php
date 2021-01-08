<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Collect;
use App\Models\Good;
use App\Transformers\CollectTransformer;
use Illuminate\Http\Request;

class CollectController extends BaseController
{
    /**
     * 收藏和取消收藏
     */
    public function collect(Good $goods)
    {
        $uid = auth('api')->id();
        // 查询是否收藏过
        $check = Collect::where('user_id', $uid)->where('goods_id', $goods->id)->first();

        // 如果已经收藏过了, 就是取消收藏
        if ($check) {
            $check->delete();
            return $this->response()->noContent();
        } else {

        }

        Collect::create([
            'user_id' => $uid,
            'goods_id' => $goods->id
        ]);

        return $this->response()->created();
    }

    /**
     * 我的收藏
     */
    public function myCollects()
    {
        $collects = Collect::where('user_id', auth('api')->id())
            ->with('goods:id,title,description,cover,price')
            ->paginate(10);

        return $this->paginator($collects, new CollectTransformer());
    }
}
