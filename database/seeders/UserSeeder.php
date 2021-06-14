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
            'name' => 'Volodymyr Yevsiuk',
            'email' => 'vov2706@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
            'profile_photo_path' => 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y&s=35'
        ]);
    }
}
