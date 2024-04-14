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
                'name' => 'Strawberry Shake',
                'description' => '',
                'image' => 'strawberry shake.jpg',
                'price' => 180.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sagot Gulaman',
                'description' => '',
                'image' => 'sagot gulaman.jpg',
                'price' => 70.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Shirley Temple',
                'description' => '',
                'image' => 'shirley temple.jfif',
                'price' => 85.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Kalamansi Slush',
                'description' => '',
                'image' => 'kalamansi slush.jfif',
                'price' => 115.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Iced Coffee Float',
                'description' => '',
                'image' => 'Ice coffee float.jfif',
                'price' => 125.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Fresh Fruit Shake',
                'description' => '',
                'image' => 'fresh fruit shake.jpg',
                'price' => 155.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Juice in Can',
                'description' => 'Pineapple/4seasons/Mango',
                'image' => 'juice in can.jpg',
                'price' => 70.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Softdrinks',
                'description' => '08oz',
                'image' => 'soft drinks 8oz.jfif',
                'price' => 18.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Softdrinks',
                'description' => '1.5liter',
                'image' => 'soft drinks 1.5.jpg',
                'price' => 130.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Bottled Water',
                'description' => '500ml',
                'image' => 'bottled water 500.png',
                'price' => 22.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Bottled Water',
                'description' => '330ml',
                'image' => 'bottled water 300.jpg',
                'price' => 15.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Mango Queen',
                'description' => '',
                'image' => 'mango queen.jpg',
                'price' => 160.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Cucumber Lemonade',
                'description' => '',
                'image' => 'Cucumber-Lemonade-5.jpg',
                'price' => 200.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Iced Tea House Blend',
                'description' => '',
                'image' => 'iced tea house blend.jfif',
                'price' => 200.00,
                'category_id' => 1, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            
            [
                'name' => 'Sizzling Mushroom',
                'description' => 'With Tofu',
                'image' => 'Sizzling mushroom only.jfif',
                'price' => 305.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Beef Bulalo',
                'description' => '',
                'image' => 'Sizzling beef bulalo.jpg',
                'price' => 750,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Sisig Squid',
                'description' => 'Minced squid parts with chili, onion in sizzling plate',
                'image' => 'Sizzling sisig squid.jpg',
                'price' => 295.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Pusit (Squid)',
                'description' => 'Sauteed spicy squid cuts served in a sizzling plate',
                'image' => 'Sizzling stuffed squid.jfif',
                'price' => 295.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Breaded Pork with Gravy',
                'description' => 'Deep fried breaded pork covered with hot gravy sauce',
                'image' => 'sizzling breaded porkchop.jpg',
                'price' => 395.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Seafoods',
                'description' => '',
                'image' => 'sizzling seafoods.jfif',
                'price' => 565.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Tanigue Steak',
                'description' => 'Steak-cut tanigue with special sauce',
                'image' => 'Sizzling-Tanigue-Steak.jpg',
                'price' => 315.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Fried Chicken',
                'description' => 'Fried chicken in creamy sauce with chopped vegetables',
                'image' => 'sizzling fried chicken.jpg',
                'price' => 355.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Laing with Tofu',
                'description' => 'Gabi leaves cooked in coconut milk served with crisp-fried tofu',
                'image' => 'sizzling laing with tofu.jfif',
                'price' => 265.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Chicken Diablo',
                'description' => 'Half chicken in a sizzling plate covered with special spicy red sauce',
                'image' => 'sizzling chicken diablo.jfif',
                'price' => 335.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Spicy Chicken Wings',
                'description' => 'Fried chicken wings in hot & spicy sauce in sizzling platter',
                'image' => 'sizzling-spicy-wings-recipe-2.jpg',
                'price' => 285.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Tofu',
                'description' => 'Crisp-fried tofu, chili peppers, mayonnaise and oyster sauce',
                'image' => 'sizzling tofu.jpg',
                'price' => 245.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Pork Sisig (Regular)',
                'description' => 'Minced crispy pork mask with chili, onion & liver in sizzling plate',
                'image' => 'Sizzling Pork Sisig.avif',
                'price' => 225.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Pork Sisig (Special)',
                'description' => 'Minced crispy pork mask with chili, onion & liver in sizzling plate (with egg)',
                'image' => 'Sizzling Pork Sisig.avif',
                'price' => 245.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Stuffed Squid (Per Gram)',
                'description' => 'Squid stuffed with chopped sausage bacon, carrot & onion',
                'image' => 'Sizzling stuffed squid.jfif',
                'price' => 1.60,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Hotdog',
                'description' => 'Sliced hotdog coered with spicy red sauce and mixed vegetable on the side',
                'image' => 'Sizzling-Hotdog1.jpg',
                'price' => 195.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Corn with Cheese',
                'description' => 'Sauteed whole corn kernels topped with grated cheese',
                'image' => 'sizzling corn with cheese.jpg',
                'price' => 195.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],
            [
                'name' => 'Sizzling Mushroom',
                'description' => 'Sauteed button mushroom topped with garlic bits',
                'image' => 'Sizzling mushroom only.jfif',
                'price' => 195.00,
                'category_id' => 9, // Assuming there is a category with ID 1
                'orders' => 0,
                'archived_at' => null
            ],

            // Add more entries as needed
        ];

        // Using Query Builder to insert data
        $this->db->table('menu')->insertBatch($data);
    }
}
