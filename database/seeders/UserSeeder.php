<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();

        DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => 'Volodymyr Yevsiuk',
            'email' => 'vov2706@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
    }
}
