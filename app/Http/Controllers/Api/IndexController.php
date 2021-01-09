<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Good;
use App\Models\Slide;
use App\Transformers\GoodTransformer;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    /**
     * 首页数据
     */
    public function index(Request $request)
    {
        // 轮播图数据
        $slides = Slide::where('status', 1)
            ->orderBy('seq')
            ->get();

        // 分类数据
        $categories = cache_category();

        // 推荐
        $recommend = $request->query('recommend', false);
        // 热销
        $sales = $request->query('sales', false);
        // 最新
        $new = $request->query('new', false);

        if ($recommend === false && $sales === false && $new === false) {
            $sales = true;
        }

        $goods = Good::where('is_on', 1)
            ->select('id', 'title', 'price', 'stock', 'sales', 'cover', 'description')
            ->when($recommend, function ($query) use ($recommend) {
                $query->where('is_recommend', 1);
            })
            ->when($sales, function ($query) use ($sales) {
                $query->orderBy('sales', 'desc');
            })
            ->when($new, function ($query) use ($new) {
                $query->orderBy('created_at', 'desc');
            })
            ->withCount('collects')
            ->simplePaginate(10)
            ->appends([
                'recommend' => $recommend,
                'sales' => $sales,
                'new' => $new,
            ]);

        return $this->response->array([
            'slides' => $slides,
            'categories' => $categories,
            'goods' => $goods
        ]);
    }
}
