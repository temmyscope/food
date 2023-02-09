<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('utc')->toDateTimeString();

        Ingredient::insert([
            [//Add Beef
                'name' => 'Beef',
                'initial_qty' => 20000,
                'available_qty' => 20000,
                'needs_restock' => 'false',
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [//Add Cheese
                'name' => 'Cheese',
                'initial_qty' => 5000,
                'available_qty' => 5000,
                'needs_restock' => 'false',
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [//Add Onion
                'name' => 'Onion',
                'initial_qty' => 1000,
                'available_qty' => 1000,
                'needs_restock' => 'false',
                'created_at'=> $now,
                'updated_at'=> $now
            ],
        ]);
    }
}
