<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充菜单数据
        $menus = [
            [
                'name' => '用户管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '用户列表',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                ]
            ],
            [
                'name' => '分类管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '分类列表',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                    [
                        'name' => '添加分类',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                ]
            ],
            [
                'name' => '商品管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '商品列表',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                    [
                        'name' => '添加商品',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                ]
            ],
            [
                'name' => '评价管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '评价列表',
                        'group' => 'menu',
                        'level' => 2,
                    ]
                ]
            ],
            [
                'name' => '订单管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '订单列表',
                        'group' => 'menu',
                        'level' => 2,
                    ]
                ]
            ],
            [
                'name' => '轮播管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '轮播列表',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                    [
                        'name' => '添加轮播',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                ]
            ],
            [
                'name' => '菜单管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '菜单列表',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                    [
                        'name' => '添加菜单',
                        'group' => 'menu',
                        'level' => 2,
                    ],
                ]
            ],
        ];

        // 循环菜单数组, 插入数据库
        foreach ($menus as $one) {
            $children = $one['children'];
            unset($one['children']);
            $one_menu = Category::create($one);
            $one_menu->children()->createMany($children);
        }

        // 清空缓存
        forget_cache_category();
    }
}
