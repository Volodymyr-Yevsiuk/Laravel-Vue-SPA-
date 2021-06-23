<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::factory()->count(15)->create();

        $companies = Company::all();
        foreach ($companies as $key => $company) {
            $user = $company->user;
            $user->update(['role_id' => 2]);
        }
    }
}
