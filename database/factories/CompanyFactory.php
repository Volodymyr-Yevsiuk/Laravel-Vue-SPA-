<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $employees = collect([10, 20, 30, 40, 50, 100, 200, 500]);
        $users = \App\Models\User::pluck('id');

        return [
            'name' => $this->faker->company,
            'employees' => $employees->random(),
            'image' => '1.jpg',
            'description' => $this->faker->realText(1000),
            'address' => $this->faker->address,
            'user_id' => $users->random()
        ];
    }
}
