<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建超级管理员用户
        $user = User::create([
            'name' => '超级管理员',
            'email' => 'super@a.com',
            'password' => bcrypt('123123')
        ]);
        // 给用户分配角色
        $user->assignRole('super-admin');

        // 创建普通用户
        User::create([
            'name' => '测试用户',
            'email' => 'test@a.com',
            'password' => bcrypt('123123')
        ]);
    }
}
