<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // 定时检测订单状态, 超过10分钟未支付的, 作废掉
        // 真实的项目中, 不会这么做, 真实的项目一般会使用长连接,订单过期了, 直接作废
        $schedule->call(function () {
            $orders = Order::where('status', 1)
                ->where('created_at', '<', date('Y-m-d H:i:s', time() - 3600 * 24))
                ->with('orderDetails.goods')
                ->get();

            // 循环订单, 修改订单状态, 还原商品库存
            try {
                DB::beginTransaction();

                foreach ($orders as $order) {
                    $order->status = 5;
                    $order->save();

                    // 还原商品库存
                    foreach ($order->orderDetails as $details) {
                        $details->goods->increment('stock', $details->num);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
