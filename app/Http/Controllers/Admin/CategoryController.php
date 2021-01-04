<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * 分类列表
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        if ($type == 'all') {
            return cache_category_all();
        } else {
            return cache_category();
        }
    }

    /**
     * 添加分类 最大2级分类
     */
    public function store(Request $request)
    {
        $insertData = $this->checkInput($request);
        if (!is_array($insertData)) return $insertData;

        Category::create($insertData);

        return $this->response->created();
    }

    /**
     * 分类详情
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * 更新分类
     */
    public function update(Request $request, Category $category)
    {
        $updateData = $this->checkInput($request);
        if (!is_array($updateData)) return $updateData;

        $category->update($updateData);

        return $this->response->noContent();
    }

    /**
     * 验证提交的参数
     */
    protected function checkInput($request)
    {
        // 验证参数
        $request->validate([
            'name' => 'required|max:16'
        ], [
            'name.required' => '分类名称 不能为空'
        ]);

        // 获取分组
        $group = $request->input('group', 'goods');

        // 获取pid
        $pid = $request->input('pid', 0);

        // 计算level
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1);

        // 不能超过3级分类
        if ($level > 2) {
            return $this->response->errorBadRequest('不能超过二级分类');
        }

        return [
            'name' => $request->input('name'),
            'pid' => $pid,
            'level' => $level,
            'group' => $group
        ];
    }

    /**
     * 状态禁用和启用
     */
    public function status(Category $category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return $this->response->noContent();
    }
}
