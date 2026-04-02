<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            [
                'name' => 'Acacia',
                'scientific_name' => 'Acacia mangium',
                'description' => 'Fast-growing tropical hardwood widely planted in Sarawak for reforestation and timber production. Thrives in degraded soils and is commonly used in plantation forestry.',
                'base_price' => 1.50,
                'low_stock_threshold' => 200,
            ],
            [
                'name' => 'Batai',
                'scientific_name' => 'Paraserianthes falcataria',
                'description' => 'One of the fastest-growing tropical trees, commonly planted for pulpwood, light construction timber, and shade. Native to the Moluccas and widely cultivated across Borneo.',
                'base_price' => 2.00,
                'low_stock_threshold' => 150,
            ],
            [
                'name' => 'Belian / Borneo Ironwood',
                'scientific_name' => 'Eusideroxylon zwageri',
                'description' => 'Legendary ironwood of Borneo, prized for its extreme durability and resistance to rot. A slow-growing, endangered dipterocarp that can live over 1,000 years. Traditionally used for longhouse construction and shingle roofing.',
                'base_price' => 50.00,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Engkabang Jantong',
                'scientific_name' => 'Shorea macrophylla',
                'description' => 'A dipterocarp species native to Borneo valued for its illipe nut oil and quality timber. Important for both commercial agroforestry and biodiversity conservation in Sarawak.',
                'base_price' => 15.00,
                'low_stock_threshold' => 30,
            ],
            [
                'name' => 'Kelampayan',
                'scientific_name' => 'Neolamarckia cadamba',
                'description' => 'Fast-growing pioneer species native to Southeast Asia. Used for lightweight timber, plywood, and paper pulp. Produces distinctive orange ball-shaped flowers attractive to pollinators.',
                'base_price' => 5.00,
                'low_stock_threshold' => 100,
            ],
        ];

        foreach ($species as $s) {
            Species::create($s);
        }
    }
}
