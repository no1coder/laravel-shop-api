<?php


namespace App\Transformers;


use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'pid' => $category->pid,
            'name' => $category->name,
        ];
    }
}
