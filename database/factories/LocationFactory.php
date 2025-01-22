<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location' => rand(10,99).'.'.rand(10,99).rand(10,99).rand(10,99).', '.rand(10,99).'.'.rand(10,99).rand(10,99).rand(10,99),
            'id_type' => Type::get()->random()->id,
            'id_category' => Category::get()->random()->id,
            'title' => $this->faker->title,
            'description' => $this->faker->text(100),
            'photo_path' => 'photos/nelOpxqswjxvosaAupnJBB9pHJ7P8qCZ2C2gCHp1.jpg',
            'start_time' => rand(10,24).':'.rand(10,59),
            'end_time' => rand(10,24).':'.rand(10,59),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
