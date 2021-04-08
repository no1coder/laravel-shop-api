<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SlideRequest;
use App\Models\Slide;
use App\Transformers\SlideTransformer;
use Illuminate\Http\Request;

class SlideController extends BaseController
{
    /**
     * 列表
     */
    public function index(Request $request)
    {
        $slides = Slide::orderBy('updated_at', 'desc')
            ->paginate($request->query('pageSize', 10), ['*'], 'current');

        return $this->response->paginator($slides, new SlideTransformer());
    }

    /**
     * 添加
     */
    public function store(SlideRequest $request)
    {
        // 查询最大的seq
        $max_seq = Slide::max('seq') ?? 0;
        $max_seq++;

        $request->offsetSet('seq', $max_seq);
        Slide::create($request->all());

        return $this->response->created();
    }

    /**
     * 详情
     */
    public function show(Slide $slide)
    {
        return $this->response->item($slide, new SlideTransformer());
    }

    /**
     * 更新
     */
    public function update(SlideRequest $request, Slide $slide)
    {
        if ($slide->id < 6) $this->response->errorBadRequest('系统数据禁止编辑, 请自行创建数据');

        $slide->update($request->all());
        return $this->response->noContent();
    }

    /**
     * 删除
     */
    public function destroy(Slide $slide)
    {
        if ($slide->id < 6) $this->response->errorBadRequest('系统数据禁止编辑, 请自行创建数据');
        $slide->delete();
        return $this->response->noContent();
    }

    /**
     * 排序
     */
    public function seq(Request $request, Slide $slide)
    {
        $slide->seq = $request->input('seq', 1);
        $slide->save();
        return $this->response->noContent();
    }

    /**
     * 禁用和启用
     */
    public function status(Slide $slide)
    {
        if ($slide->id < 6) $this->response->errorBadRequest('系统数据禁止编辑, 请自行创建数据');

        $slide->status = $slide->status == 1 ? 0 : 1;
        $slide->save();
        return $this->response->noContent();
    }
}
