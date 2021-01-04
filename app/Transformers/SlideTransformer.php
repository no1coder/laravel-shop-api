<?php


namespace App\Transformers;


use App\Models\Slide;
use League\Fractal\TransformerAbstract;

class SlideTransformer extends TransformerAbstract
{
    public function transform(Slide $slide)
    {
        return [
            'id' => $slide->id,
            'title' => $slide->title,
            'url' => $slide->url,
            'img' => $slide->img,
            'img_url' => oss_url($slide->img),
            'seq' => $slide->seq,
            'status' => $slide->status,
            'created_at' => $slide->created_at,
            'updated_at' => $slide->updated_at,
        ];
    }
}
