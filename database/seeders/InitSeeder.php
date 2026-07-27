<?php

namespace Database\Seeders;

use Database\Seeders\KibSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserInitSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
					RolePermissionSeeder::class,
					UserInitSeeder::class,
					KibSeeder::class
				]);
    }
}
