<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Good;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * 购物车商品列表
     */
    public function index()
    {
        $carts = Cart::where('user_id', auth('api')->id())->get();
        return $this->response->collection($carts, new CartTransformer());
    }

    /**
     * 加入购物车
     */
    public function store(Request $request)
    {
        $request->validate([
            'goods_id' => 'required|exists:goods,id',
            'num' => [
                'gte:1',
                // 数量不能超过商品的库存
                function($attribute, $value, $fail) use ($request){
                    $goods = Good::find($request->goods_id);
                    if ($value > $goods->stock) {
                        $fail("数量 不能操作库存");
                    }
                }
            ]
        ], [
            'goods_id.required' => '商品ID 不能为空',
            'goods_id.exists' => '商品 不存在',
        ]);

        // 查询购物车数据是否存在
        $cart = Cart::where('user_id', auth('api')->id())
            ->where('goods_id', $request->input('goods_id'))
            ->first();

        if (!empty($cart)) {
            $num = $request->input('num', 1);
            $cart->increment('num', $num);
            return $this->response->noContent();
        }

        Cart::create([
            'user_id' => auth('api')->id(),
            'goods_id' => $request->input('goods_id'),
            'num' => $request->input('num', 1)
        ]);

        return $this->response->created();
    }

    /**
     * 数量增加减少
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'num' => [
                'required',
                'gte:1',
                function ($attribute, $value, $fail) use ($cart) {
                    if ($value > $cart->goods->stock) {
                        $fail('数量 不能超过最大库存');
                    }
                }
            ]
        ], [
            'num.required' => '数量 不能为空',
            'num.gte' => '数量 最少是1',
        ]);

        $cart->num = $request->input('num');
        $cart->save();

        return $this->response->noContent();
    }

    /**
     * 移除购物车
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->response->noContent();
    }

    /**
     * 购物车选中和取消选中
     */
    public function checked(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array'
        ], [
            'cart_ids.required' => '购物车ID 不能为空',
            'cart_ids.array' => '购物车ID 必须是数组',
        ]);

        $cart_ids = $request->input('cart_ids');

        $uid = auth('api')->id();

        // 所有先不选中
        Cart::where('user_id', $uid)->update(['is_checked' => 0]);

        // 在选中提交的
        Cart::where('user_id', $uid)->whereIn('id', array_values($cart_ids))->update(['is_checked' => 1]);

        return $this->response->noContent();
    }
}
