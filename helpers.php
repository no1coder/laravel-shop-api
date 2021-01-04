<?php

use App\Models\Category;

/**
 * 所有分类原始数据
 */
if (!function_exists('categoryTree')) {
    function categoryTree($group = 'goods', $status = false)
    {
        $categories = Category::select(['id', 'pid', 'name', 'level', 'status'])
            ->when($status !== false, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where('group', $group)
            ->where('pid', 0)
            ->with([
                'children' => function($query) use ($status) {
                    $query->select(['id','pid','name','level','status'])
                        ->when($status !== false, function ($query) use ($status) {
                            $query->where('status', $status);
                        });
                },
                'children.children' => function($query) use ($status) {
                    $query->select(['id','pid','name','level','status'])
                        ->when($status !== false, function ($query) use ($status) {
                        $query->where('status', $status);
                    });
                }
            ])
            ->get();

        return $categories;
    }
}

/**
 * 缓存没被禁用的分类
 */
if (!function_exists('cache_category')) {
    function cache_category ()
    {
        return cache()->rememberForever('cache_category', function () {
            return categoryTree('goods', 1);
        });
    }
}

/**
 * 缓存所有的分类
 */
if (!function_exists('cache_category_all')) {
    function cache_category_all ()
    {
        return cache()->rememberForever('cache_category_all', function () {
            return categoryTree('goods');
        });
    }
}

/**
 * 缓存没被禁用的菜单
 */
if (!function_exists('cache_category_menu')) {
    function cache_category_menu ()
    {
        return cache()->rememberForever('cache_category_menu', function () {
            return categoryTree('menu', 1);
        });
    }
}

/**
 * 缓存所有的菜单
 */
if (!function_exists('cache_category_menu_all')) {
    function cache_category_menu_all ()
    {
        return cache()->rememberForever('cache_category_menu_all', function () {
            return categoryTree('menu');
        });
    }
}

/**
 * 清空分类缓存
 */
if (!function_exists('forget_cache_category')) {
    function forget_cache_category ()
    {
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
        cache()->forget('cache_category_menu');
        cache()->forget('cache_category_menu_all');
    }
}

if (!function_exists('oss_url')) {
    function oss_url($key)
    {
        // 如果没有$key
        if (empty($key)) return '';

        // 如果$key包含了http等, 是一个完整的地址, 直接返回
        if (strpos($key, 'http://') !== false
            || strpos($key, 'https://')  !== false
            || strpos($key, 'data:image')  !== false) {
            return $key;
        }

        return config('filesystems')['disks']['oss']['bucket_url'] . '/' . $key;
    }
}

/**
 * 城市相关的缓存信息
 */
if (!function_exists('city_cache')) {
    function city_cache($pid = 0)
    {
        return cache()->rememberForever('city_children' . $pid, function () use ($pid) {
            return \App\Models\City::where('pid', $pid)->get()->keyBy('id');
        });
    }
}

/**
 * 通过3, 4级 ID, 查询完整的省市县信息
 */
if (!function_exists('city_name')) {
    function city_name($city_id)
    {
        $city = \App\Models\City::where('id', $city_id)->with('parent.parent.parent')->first();
        $arr = [
            $city['parent']['parent']['parent']['name'] ?? '',
            $city['parent']['parent']['name'] ?? '',
            $city['parent']['name'] ?? '',
            $city['name'] ?? '',
        ];
        return trim(implode(' ', $arr));
    }
}
