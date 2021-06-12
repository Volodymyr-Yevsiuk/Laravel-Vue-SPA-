<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => Str::uuid(), 
                'name' => 'Guest'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'AuthUser'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'Head'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'Admin'
            ]
        ]);
    }
}
