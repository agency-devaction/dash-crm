<?php

namespace Database\Seeders;

use App\Enum\Can;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->withPermissions(Can::BE_AN_ADMIN)
            ->create([
                'name'  => 'Admin CRM',
                'email' => 'admin@crm.com',
            ]);
    }
}