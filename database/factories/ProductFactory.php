<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = \App\Models\User::pluck('id');
        $companies = \App\Models\Company::pluck('id');

        return [
            'name' => $this->faker->sentence(rand(1, 3)),
            'price' => rand(20, 100),
            'image' => $this->faker->imageUrl(250, 100),
            'description' => $this->faker->realText(500),
            'company_id' => $companies->random(),
            'user_id' => $users->random()
        ];
    }
}
