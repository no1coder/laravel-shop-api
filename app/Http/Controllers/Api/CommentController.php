<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /**
     * 评论
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'goods_id' => 'required',
            'content' => 'required'
        ], [
            'goods_id.required' => '商品id 不能为空',
            'content.required' => '评论内容 不能为空',
        ]);

        // 只有确认收货才可以评论 status=4
        if ($order->status != 4) {
            return $this->response->errorBadRequest('订单状态异常');
        }

        // 要评论的商品必须是这个订单里面
        if (!in_array($request->input('goods_id'), $order->orderDetails()->pluck('goods_id')->toArray())) {
            return $this->response->errorBadRequest('此订单不包含该商品');
        }

        // 已经评论过的, 不能评论
        $checkComment = Comment::where('user_id', auth('api')->id())
            ->where('order_id', $order->id)
            ->where('goods_id', $request->input('goods_id'))
            ->count();

        if ($checkComment > 0) {
            return $this->response->errorBadRequest('此商品已经评论过了');
        }

        // 生成评论数据
        $request->offsetSet('user_id', auth('api')->id());
        $request->offsetSet('order_id', $order->id);
        Comment::create($request->all());

        return $this->response->created();
    }
}
