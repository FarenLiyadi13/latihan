<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order_status = [
            [
                'name' => 'approved',
                'created_at' => date('Y-m-d h:i:s'),
                'deleted_at' => date('Y-m-d h:i:s')

            ],
            [
                'name' => 'progress',
                'created_at' => date('Y-m-d h:i:s'),
                'deleted_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'waiting',
                'created_at' => date('Y-m-d h:i:s'),
                'deleted_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'rejected',
                'created_at' => date('Y-m-d h:i:s'),
                'deleted_at' => date('Y-m-d h:i:s')
            ],
        ];
        OrderStatus::insert($order_status);
    }
}
