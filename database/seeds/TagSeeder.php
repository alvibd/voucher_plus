<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'baby products', 'cosmetics', 'shoes', 'footwear',
            'pizza', 'calisthenics', 'fitness', 'interior decoration',
            'bikes', 'motorcycles', 'kitchen appliances', 'burger',
            'glasses', 'sun glasses', 'mattress', 't-shirts',
            'jeans', 'cap', 'hat', 'weddings',
            'photography', 'community center','vacation', 'tours',
            'toiletries', 'confectionaries', 'foods', 'fine dining'
        ];

        $inserts = array();

        foreach ($tags as $tag) {
           array_push($inserts, ['name' => $tag]);
        }

        DB::table('tags')->insert($inserts);
    }
}
