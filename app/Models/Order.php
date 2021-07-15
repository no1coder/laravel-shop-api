<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 可以批量赋值的字段
    protected $fillable = ['user_id', 'order_no', 'amount', 'address_id', 'status', 'trade_no', 'pay_type', 'pay_time'];

    /**
     * 所属用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 订单拥有的订单细节
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }

    /**
     * 订单所关联的地址
     */
    public function orderAddress()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    /**
     * 订单远程一对多, 关联的商品
     */
    public function goods()
    {
        return $this->hasManyThrough(
            Good::class, // 最终关联的模型
            OrderDetails::class, // 中间模型
            'order_id', // 中间模型和本模型关联的外键
            'id', // 最终关联模型的外键
            'id', // 本模型和中间模型关联的键
            'goods_id' // 中间表和最终模型关联的一个键
        );
    }
}
