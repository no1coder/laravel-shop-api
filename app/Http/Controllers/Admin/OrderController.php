<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Mail\OrderPost;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends BaseController
{
    /**
     * 订单列表
     */
    public function index(Request $request)
    {
        // 查询条件
        $order_no = $request->input('order_no');
        $trade_no = $request->input('trade_no');
        $status = $request->input('status');

        $orders = Order::when($order_no, function ($query) use ($order_no) {
                $query->where('order_no', $order_no);
            })
            ->when($trade_no, function ($query) use ($trade_no) {
                $query->where('trade_no', $trade_no);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($request->pageSize ?? 10);

        return $this->response->paginator($orders, new OrderTransformer());
    }

    /**
     * 订单详情
     */
    public function show(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }

    /**
     * 发货
     */
    public function post(Request $request, Order $order)
    {
        // 验证提交的参数
        $request->validate([
            'express_type' => 'required|in:SF,YTO,YD',
            'express_no' => 'required',
        ], [
            'express_type.required' => '快递类型 必填',
            'express_type.in' => '快递类型 只能是:SF YTO YD',
            'express_no.required' => '快递单号 必填'
        ]);

         // 发货
//        $order->express_type = $request->input('express_type');
//        $order->express_no = $request->input('express_no');
//        $order->status = 3; // 发货状态
//        $order->save();
//        // 发货之后, 邮件提醒
//        Mail::to($order->user)->queue(new OrderPost($order));

        // 使用事件辅助函数分发
//        event(new \App\Events\OrderPost(
//            $order,
//            $request->input('express_type'),
//            $request->input('express_no')
//        ));

        // 使用事件分发
        \App\Events\OrderPost::dispatch(
            $order,
            $request->input('express_type'),
            $request->input('express_no')
        );

        return $this->response->noContent();
    }
}
