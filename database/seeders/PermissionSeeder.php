<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'id' => Str::uuid(), 
                'name' => 'VIEW_PRODUCTS'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'VIEW_COMPANIES'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'VIEW_USERS'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'ADD_PRODUCT'
            ],
            [
                'id' => Str::uuid(), 
                'name' => 'ADD_COMPANY'
            ]
        ]);
    }
}
