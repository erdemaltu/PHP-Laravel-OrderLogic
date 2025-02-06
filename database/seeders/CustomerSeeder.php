<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\File;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // JSON dosyasının yolu
        $jsonPath = database_path('seeders/data/customers.json');

        // JSON dosyasını oku
        $jsonData = File::get($jsonPath);

        // JSON verisini diziye dönüştür
        $customers = json_decode($jsonData, true);

        // Her bir müşteri verisini veritabanına ekle
        foreach ($customers as $customer) {
            Customer::create([
                'id' => $customer['id'],
                'name' => $customer['name'],
                'since' => $customer['since'],
                'revenue' => $customer['revenue'],
            ]);
        }
    }
}
