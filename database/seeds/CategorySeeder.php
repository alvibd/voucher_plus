<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->create() as $category)
        {
            DB::table('categories')->insert($category);
        }
    }

    protected function create()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Food & Beverages',
                'description'=> null,
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Resturants',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Coffee Shop',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Juice Bars',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'name' => 'Fast Foods',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'Thai Cuisines',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'name' => 'Chinese Cuisines',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'name' => 'Indian Cuisines',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'name' => 'Desi Cuisines',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 10,
                'name' => 'Bakery & Pastries',
                'description'=> null,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 11,
                'name' => 'Fashion',
                'description'=> null,
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 12,
                'name' => 'Clothings & Garments',
                'description'=> null,
                'category_id' => 11,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 13,
                'name' => 'Watches',
                'description'=> null,
                'category_id' => 11,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 14,
                'name' => 'Jewelaries',
                'description'=> null,
                'category_id' => 11,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 15,
                'name' => 'Lifestyles',
                'description'=> null,
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 16,
                'name' => 'Spa & Parlours',
                'description'=> null,
                'category_id' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 19,
                'name' => 'Travel & Tourism',
                'description'=> null,
                'category_id' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 20,
                'name' => 'Hotels & Inns',
                'description'=> null,
                'category_id' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 21,
                'name' => 'Hair Salons',
                'description'=> null,
                'category_id' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 22,
                'name' => 'Entertainment',
                'description'=> null,
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 23,
                'name' => 'Amusement Parks',
                'description'=> null,
                'category_id' => 22,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 24,
                'name' => 'Picnic Spots & Resorts',
                'description'=> null,
                'category_id' => 22,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 25,
                'name' => 'Museums',
                'description'=> null,
                'category_id' => 22,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 26,
                'name' => 'Online Media Services',
                'description'=> null,
                'category_id' => 22,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        return $categories;
    }
}
