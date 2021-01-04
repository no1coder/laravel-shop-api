<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\LaravelPay\Facades\Pay;

class PayController extends BaseController
{
    /**
     * 支付
     */
    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'type' => 'required|in:aliyun,wechat',
        ], [
            'type.required' => '支付类型 不能为空',
            'type.in' => '支付类型 只能是 aliyun wechat',
        ]);

        // 如果订单状态不是 1 , 直接返回
        if ($order->status != 1) {
            return $this->response->errorBadRequest('订单状态异常, 请重新下单');
        }

        $title = $order->goods()->first()->title . ' 等 ' . $order->goods()->count() . ' 件商品';

        if ($request->input('type') == 'aliyun') {
            $order = [
                'out_trade_no' => $order->order_no,
                'total_amount' => $order->amount / 100,
                'subject' => $title
            ];

            return Pay::alipay()->scan($order);
        }

        if ($request->input('type') == 'wechat') {
            $order = [
                'out_trade_no' => $order->order_no,
                'total_fee' => $order->amount,
                'body' => $title,
            ];

            return Pay::wechat()->scan($order);
        }
    }

    /**
     * 支付宝支付成功之后的回调
     */
    public function notifyAliyun(Request $request)
    {
        $alipay = Pay::alipay();

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！

            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况

            // 判断支付状态
            if ($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED') {
                // 查询订单
                $order = Order::where('order_no', $data->out_trade_no)->first();

                // 更新订单数据
                $order->update([
                    'status' => 2,
                    'pay_time' => $data->gmt_payment,
                    'pay_type' => '支付宝',
                    'trade_no' => $data->trade_no
                ]);
            }

            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $alipay->success();// laravel 框架中请直接 `return $alipay->success()`
    }

    /**
     * 微信支付成功之后的回调
     */
    public function notifyWechat(Request $request)
    {
        $wechat = Pay::wechat();

        try{
            $data = $wechat->verify(); // 是的，验签就这么简单！

            Log::info($data);

            // 判断支付状态
            if ($data->return_code == 'SUCCESS') {
                // 查询订单
                $order = Order::where('order_no', $data->out_trade_no)->first();

                // 更新订单数据
                $order->update([
                    'status' => 2,
                    'pay_time' => now(),
                    'pay_type' => '微信',
                    'trade_no' => $data->transaction_id
                ]);
            }

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $wechat->success();// laravel 框架中请直接 `return $alipay->success()`
    }

    /**
     * 轮询查询订单状态, 是否完成支付
     */
    public function payStatus(Order $order)
    {
        return $order->status;
    }
}
