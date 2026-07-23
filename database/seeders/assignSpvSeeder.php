<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class assignSpvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
				$user->assignRole('admin');

				 $user = User::find(2);
				$user->assignRole('spv');

				 $user = User::find(3);
				$user->assignRole('staf');
    }
}
