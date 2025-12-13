<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryCitySeeder extends Seeder
{
    /**
     * Seed countries with their related cities.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'United Arab Emirates',
                'code' => 'UAE',
                'cities' => [
                    'Dubai',
                    'Abu Dhabi',
                    'Sharjah',
                    'Ajman',
                    'Fujairah',
                    'Ras Al Khaimah',
                    'Umm Al Quwain',
                ],
            ],
            [
                'name' => 'Saudi Arabia',
                'code' => 'KSA',
                'cities' => [
                    'Riyadh',
                    'Jeddah',
                    'Mecca',
                    'Medina',
                    'Dammam',
                ],
            ],
        ];

        foreach ($countries as $countryData) {
            $country = Country::updateOrCreate(
                ['name' => $countryData['name']]
            );

            foreach ($countryData['cities'] as $cityName) {
                City::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $cityName,
                    ]
                );
            }
        }
    }
}
