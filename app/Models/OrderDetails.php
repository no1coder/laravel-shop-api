<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    // 可批量赋值的字段
    protected $fillable = ['order_id', 'goods_id', 'price', 'num'];

    /**
     * 细节所属订单主表
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * 细节所关系的商品
     */
    public function goods()
    {
        return $this->hasOne(Good::class, 'id', 'goods_id');
    }
}
