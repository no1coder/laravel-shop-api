<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imgs = [
            'ad/2020-0820-5f3dfe85ae3fd.png',
            'ad/2020-0820-5f3dfe9530bfd.png',
            'ad/2020-0820-5f3dfe7756215.png',
            'ad/2020-0825-5f44b35f595a4.png',
            'ad/2020-0827-5f474a7259d67.png',
        ];
        foreach ($imgs as $k => $v) {
            Slide::create([
                'title' => '融职图书商城',
                'url' => 'https://www.eduwork.cn',
                'img' => $v,
                'status' => 1,
                'seq' => $k + 1,
            ]);
        }
    }
}
