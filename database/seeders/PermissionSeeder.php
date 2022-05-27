<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 清空缓存
        app()['cache']->forget('spatie.permission.cache');

        // // 添加权限
        $permissions = [
            // 用户管理
            ['name' => 'users.index', 'cn_name' => '用户列表', 'guard_name' => 'api'],
            ['name' => 'users.show', 'cn_name' => '用户详情', 'guard_name' => 'api'],
            ['name' => 'users.lock', 'cn_name' => '用户禁用启用', 'guard_name' => 'api'],

            // 分类管理
            ['name' => 'category.status', 'cn_name' => '分类禁用启用', 'guard_name' => 'api'],
            ['name' => 'category.index', 'cn_name' => '分类列表', 'guard_name' => 'api'],
            ['name' => 'category.store', 'cn_name' => '添加分类', 'guard_name' => 'api'],
            ['name' => 'category.show', 'cn_name' => '分类详情', 'guard_name' => 'api'],
            ['name' => 'category.update', 'cn_name' => '分类更新', 'guard_name' => 'api'],

            // 商品管理
            ['name' => 'goods.on', 'cn_name' => '商品上架下架', 'guard_name' => 'api'],
            ['name' => 'goods.recommend', 'cn_name' => '商品是否推荐', 'guard_name' => 'api'],
            ['name' => 'goods.index', 'cn_name' => '商品列表', 'guard_name' => 'api'],
            ['name' => 'goods.show', 'cn_name' => '商品详情', 'guard_name' => 'api'],
            ['name' => 'goods.store', 'cn_name' => '商品添加', 'guard_name' => 'api'],
            ['name' => 'goods.update', 'cn_name' => '商品更新', 'guard_name' => 'api'],

            // 评价管理
            ['name' => 'comments.index', 'cn_name' => '评价列表', 'guard_name' => 'api'],
            ['name' => 'comments.show', 'cn_name' => '评价详情', 'guard_name' => 'api'],
            ['name' => 'comments.reply', 'cn_name' => '评价回复', 'guard_name' => 'api'],

            // 订单管理
            ['name' => 'order.index', 'cn_name' => '订单列表', 'guard_name' => 'api'],
            ['name' => 'order.show', 'cn_name' => '订单详情', 'guard_name' => 'api'],
            ['name' => 'order.post', 'cn_name' => '订单发货', 'guard_name' => 'api'],

            // 轮播图管理
            ['name' => 'slides.seq', 'cn_name' => '轮播排序', 'guard_name' => 'api'],
            ['name' => 'slides.index', 'cn_name' => '轮播列表', 'guard_name' => 'api'],
            ['name' => 'slides.store', 'cn_name' => '轮播添加', 'guard_name' => 'api'],
            ['name' => 'slides.show', 'cn_name' => '轮播详情', 'guard_name' => 'api'],
            ['name' => 'slides.update', 'cn_name' => '轮播更新', 'guard_name' => 'api'],
            ['name' => 'slides.destroy', 'cn_name' => '轮播删除', 'guard_name' => 'api'],
            ['name' => 'slides.status', 'cn_name' => '轮播禁用和启用', 'guard_name' => 'api'],

            // 菜单管理
            ['name' => 'menus.index', 'cn_name' => '菜单列表', 'guard_name' => 'api'],
        ];
        foreach ($permissions as $p) {
            Permission::create($p);
        }

        // 添加角色
        $role = Role::create(['name' => 'super-admin', 'cn_name' => '超级管理员', 'guard_name' => 'api']);

        // 为角色添加权限
        $role->givePermissionTo(Permission::all());
    }
}
