<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        
        $country = Country::first();
        $city = City::where('country_id', $country->id)->first();

        if (!$country || !$city) {
            $this->command->error('Countries and cities must be seeded first.');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'), 
                'phone_number' => '0199999999',

                'is_active' => true,
                'email_verified_at' => now(),

                'country_id' => $country->id,
                'city_id' => $city->id,

                'login_attempts' => 0,
                'blocked_until' => null,

                'remember_token' => Str::random(10),
            ]
        );

        $user->assignRole('user');
    }
}
