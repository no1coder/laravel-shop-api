<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // 指定模型关联的表名
    protected $table = 'city';

    /**
     * 子类
     */
    public function children()
    {
        return $this->hasMany(City::class, 'pid', 'id');
    }

    /**
     * 父级
     */
    public function parent()
    {
        return $this->belongsTo(City::class, 'pid', 'id');
    }
}
