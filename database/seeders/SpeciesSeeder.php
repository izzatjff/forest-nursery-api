<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            ['name' => 'Teak', 'scientific_name' => 'Tectona grandis', 'description' => 'Tropical hardwood tree prized for durable timber.', 'base_price' => 15.00, 'low_stock_threshold' => 50],
            ['name' => 'Mahogany', 'scientific_name' => 'Swietenia macrophylla', 'description' => 'Large tropical tree known for reddish-brown timber.', 'base_price' => 25.00, 'low_stock_threshold' => 30],
            ['name' => 'Neem', 'scientific_name' => 'Azadirachta indica', 'description' => 'Fast-growing tree with medicinal properties.', 'base_price' => 8.00, 'low_stock_threshold' => 100],
            ['name' => 'Banyan', 'scientific_name' => 'Ficus benghalensis', 'description' => 'Large fig tree with aerial roots.', 'base_price' => 12.00, 'low_stock_threshold' => 20],
            ['name' => 'Sandalwood', 'scientific_name' => 'Santalum album', 'description' => 'Slow-growing tree valued for fragrant heartwood.', 'base_price' => 50.00, 'low_stock_threshold' => 15],
            ['name' => 'Bamboo', 'scientific_name' => 'Bambusa vulgaris', 'description' => 'Fast-growing giant grass used for construction.', 'base_price' => 5.00, 'low_stock_threshold' => 200],
            ['name' => 'Eucalyptus', 'scientific_name' => 'Eucalyptus globulus', 'description' => 'Fast-growing tree used for timber and essential oils.', 'base_price' => 10.00, 'low_stock_threshold' => 75],
            ['name' => 'Rosewood', 'scientific_name' => 'Dalbergia sissoo', 'description' => 'Premium timber tree with beautiful grain patterns.', 'base_price' => 35.00, 'low_stock_threshold' => 20],
        ];

        foreach ($species as $s) {
            Species::create($s);
        }
    }
}
