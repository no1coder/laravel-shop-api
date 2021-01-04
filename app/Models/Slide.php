<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    // 追加额外的字段
    protected $appends = ['img_url'];

    protected $fillable = ['title', 'url', 'img', 'status', 'seq'];

    /**
     * img_url 属性修改器
     */
    public function getImgUrlAttribute()
    {
        return oss_url($this->img);
    }
}
