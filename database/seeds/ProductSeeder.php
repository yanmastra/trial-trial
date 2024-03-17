<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $code = (new \DateTime())->getTimestamp();
            $product = [
                'id' => Str::uuid()->toString(),
                'code' => $code.$i,
                'name' => "Product ".$code, 
                'stock' => 100, 
                'company_id' => '265d36d2-f7a6-493d-9278-37e726dba548', 
                'category_id' => '8a1d9492-76a3-4d0b-8dcd-9a676eb5d2f1', 
                'parent_id' => null, 
                'unit_id' => null, 
                'price' => ($i % 5 + 1) * 1000, 
                'cost_price' => ($i % 5 + 1) * 750
            ];
            Product::create($product);
        }
    }
}
