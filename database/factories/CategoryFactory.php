<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category'=>$this->faker->unique()->word.rand(1,1000),
            'id_type'=>Type::get()->random()->id
        ];
    }
}
