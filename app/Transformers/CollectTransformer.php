<?php


namespace App\Transformers;


use App\Models\Collect;
use App\Models\Order;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CollectTransformer extends TransformerAbstract
{
    public function transform(Collect $collect)
    {
        return [
            'id' => $collect->id,
            'user_id' => $collect->user_id,
            'goods_id' => $collect->goods_id,
            'goods' => $collect->goods,
            'created_at' => empty($collect->created_at) ? $collect->created_at : $collect->created_at->toDateTimeString(),
            'updated_at' => empty($collect->updated_at) ? $collect->updated_at : $collect->updated_at->toDateTimeString(),
        ];
    }
}
