<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use App\Models\Type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Type::factory(20)->create();
        //Category::factory(50)->create();
        Location::factory(100)->create();

    }
}
