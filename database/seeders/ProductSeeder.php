<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->initProduct();
    }
    public function initProduct(){
        $data = [
            [
                'category' => 'Online',
                'name' => 'Seminar Devops',
                'location' => 'Bandung',
                'schedule' => "17/08/2022",
                'price_includes' => "[Certificate,dll]",
                'price_excludes' => "[Hotel]",
                'price' => 100000,
                'description' => "Product Test Description",
                'status' => "open"
            ]
        ];

        foreach($data as $item){
            Product::updateOrCreate([
                'category' => $item['category'],
                'name' => $item['name'],
                'location' => $item['location'],
                'schedule' => $item['schedule'],
                'price_includes' => $item['price_includes'],
                'price_excludes' => $item['price_excludes'],
                'price' => $item['price'],
                'description'=> $item['description'],
                'status' => $item['status']
            ]); 
        }
    }
}
