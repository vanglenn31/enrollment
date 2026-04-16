<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::insert([
            ['role' => 'unenrolled'],
            ['role' => 'student'],
            ['role' => 'teller'],
            ['role' => 'registrar'],
            ['role' => 'admin'],
        ]);
    }
}
