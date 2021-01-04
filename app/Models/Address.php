<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // 不可批量赋值的字段
    protected $guarded = [];

    // 追加中文地址
    protected $appends = ['city_name'];

    public function getCityNameAttribute()
    {
        return city_name($this->city_id);
    }
}
