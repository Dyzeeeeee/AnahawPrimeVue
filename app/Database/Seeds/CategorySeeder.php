<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Burgers'],
            ['name' => 'Pizzas'],
            ['name' => 'Salads'],
            ['name' => 'Desserts'],
            ['name' => 'Beverages'] // You can add as many categories as needed
        ];

        // Simple Query
        // $this->db->table('categories')->insert($data);

        // Batch Query (recommended for multiple inserts)
        $this->db->table('categories')->insertBatch($data);
    }
}
