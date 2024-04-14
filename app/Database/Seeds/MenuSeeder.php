<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Classic Burger',
                'description' => 'A juicy beef burger with lettuce, tomato, and cheese.',
                'image' => 'classic_burger.jpg',
                'price' => 8.99,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 5,
                'archived_at' => null
            ],
            [
                'name' => 'Vegan Pizza',
                'description' => 'Delicious vegan pizza topped with a variety of vegetables.',
                'image' => 'vegan_pizza.jpg',
                'price' => 12.50,
                'category_id' => 2, // Assuming there is a category with ID 2
                'orders' => 3,
                'archived_at' => null
            ],
            [
                'name' => 'Grilled Chicken',
                'description' => 'Perfectly grilled chicken served with veggies and potato wedges.',
                'image' => 'grilled_chicken.jpg',
                'price' => 15.00,
                'category_id' => 3, // Assuming there is a category with ID 3
                'orders' => 8,
                'archived_at' => null
            ],
            // Add more entries as needed
        ];

        // Using Query Builder to insert data
        $this->db->table('menu')->insertBatch($data);
    }
}
