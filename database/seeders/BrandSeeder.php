<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Maruti Suzuki','Tata','Mahindra','Hyundai','Toyota','Kia',
            'BMW','Skoda','Honda','MG','Volkswagen','Renault',
            'Mercedes-Benz','Land Rover','Nissan','BYD','Citroen','VinFast',
            'Jeep','Audi','Porsche','Volvo','Lexus','Fiat',
            'Lamborghini','Mini','Force Motors','Jaguar','Ferrari','JSW'
        ];

        $remoteLogos = [
            'BYD' => 'https://logo.clearbit.com/byd.com',
            'VinFast' => 'https://logo.clearbit.com/vinfastauto.com',
            'Lexus' => 'https://logo.clearbit.com/lexus.com',
            'Force Motors' => 'https://logo.clearbit.com/forcemotors.com',
            'JSW' => 'https://logo.clearbit.com/jsw.in',
        ];

        foreach ($brands as $brand) {
            $slug = preg_replace('/[^a-z0-9]+/','-', strtolower($brand));
            $localSvg = 'images/brands/' . $slug . '.svg';
            $localPng = 'images/brands/' . $slug . '.png';
            $local = file_exists(public_path($localSvg))
                ? $localSvg
                : (file_exists(public_path($localPng)) ? $localPng : null);

            $external = $remoteLogos[$brand] ?? null;

            DB::table('brands')->updateOrInsert(
                ['slug' => $slug],
                ['name' => $brand, 'logo_path' => $local ?? $external, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
