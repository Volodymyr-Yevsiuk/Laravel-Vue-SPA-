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
        $companies = \App\Models\Company::pluck('id');

        return [
            'name' => $this->faker->text(rand(10, 30)),
            'price' => rand(20, 100),
            'image' => $this->faker->imageUrl(270, 150),
            'description' => $this->faker->realText(500),
            'company_id' => $companies->random(),
        ];
    }
}
