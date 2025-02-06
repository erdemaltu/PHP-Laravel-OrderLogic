<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // JSON dosyasının yolu
        $jsonPath = database_path('seeders/data/products.json');

        // JSON dosyasını oku
        $jsonData = File::get($jsonPath);

        // JSON verisini diziye dönüştür
        $products = json_decode($jsonData, true);

        // Her bir ürün verisini veritabanına ekle
        foreach ($products as $product) {
            Product::create([
                'id' => $product['id'],
                'name' => $product['name'],
                'category' => $product['category'],
                'price' => $product['price'],
                'stock' => $product['stock'],
            ]);
        }
    }
}
