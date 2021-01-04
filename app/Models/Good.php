<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    // 可批量赋值的字段
    protected $fillable = ['title', 'user_id', 'category_id', 'description', 'price', 'stock', 'cover', 'pics', 'is_on', 'is_recommend', 'details'];

    /**
     * 强制转换的属性
     *
     * @var array
     */
    protected $casts = [
        'pics' => 'array',
    ];

    /**
     * 追加额外的属性
     */
    protected $appends = ['cover_url'];

    /**
     * cover oss url
     */
    public function getCoverUrlAttribute()
    {
        return oss_url($this->cover);
    }

    /**
     * pics oss url
     */
    public function getPicsUrlAttribute()
    {
        // 使用集合处理每一项元素, 返回处理后的新的集合
        return collect($this->pics)->map(function ($item) {
            return oss_url($item);
        });
    }

    /**
     * 商品所属的分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * 商品所属的用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 商品所有的评价
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'goods_id', 'id');
    }
}
