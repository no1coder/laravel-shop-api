<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充分类信息
        $categories = [
            [
                'name' => '前沿',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '区块链',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => '人工智能',
                        'group' => 'goods',
                        'level' => 2
                    ],
                ]
            ],
            [
                'name' => '前端',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '小程序',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'JavaScript',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'HTML/CSS',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'Vue',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'React',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'ES6',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'Node.js',
                        'group' => 'goods',
                        'level' => 2
                    ],
                ]
            ],
            [
                'name' => '后端',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => 'Java',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'PHP',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'Python',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => 'GoLang',
                        'group' => 'goods',
                        'level' => 2
                    ],
                ]
            ],
            [
                'name' => '云计算',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '私有云',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => '公有云',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => '混合云',
                        'group' => 'goods',
                        'level' => 2
                    ],
                ]
            ],
            [
                'name' => '产品设计',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => 'UI设计',
                        'group' => 'goods',
                        'level' => 2
                    ],
                    [
                        'name' => '网站设计',
                        'group' => 'goods',
                        'level' => 2
                    ],
                ]
            ],
        ];

        // 写入到数据库
        foreach ($categories as $l1) {
            $l1_tmp = $l1;
            unset($l1_tmp['children']);
            $l2_model = Category::create($l1_tmp);
            $l2_model->children()->createMany($l1['children']);
        }

        // 清除分类缓存
        forget_cache_category();
    }
}
