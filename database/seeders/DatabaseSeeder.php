<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Good;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // 调用其他的数据填充
        $this->call(CategorySeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BookSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(CitySeeder::class);
    }
}
