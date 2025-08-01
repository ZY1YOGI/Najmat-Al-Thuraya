<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use App\Models\Role;
use App\Models\User;
use App\Models\Customer;

use App\Models\PhoneModel;
use App\Enums\PhoneStorages;
use App\Enums\PhoneColors;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedUsers();
        $this->seedCustomers();
        $this->seedPhones();
    }

    public function seedRoles(): void
    {
        Role::create(['name' => 'Super Admin', 'description' => 'This Super Admin Role.']);
        Role::create(['name' => 'Default', 'description' => 'This Default Role.']);
    }

    public function seedUsers(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'SuperAdmin',
            'email' => 'super@admin.com',
            'avatar' => 'avatars/default.png',
            'password' => bcrypt('super@admin.com'),
            'phone' => '01000000000',
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Youssef Amjad',
            'username' => 'YoussefAmjad',
            'email' => 'YoussefAmjad@admin.com',
            'avatar' => 'avatars/default.png',
            'password' => bcrypt('123456789'),
            'phone' => '01000000000',
            'role_id' => 2,
        ]);
    }

    public function seedCustomers(): void
    {
        Customer::create([
            'name' => 'Ragab',
            'store_name' => 'Najmat Al Thuraya',
            'phones' => [
                '01112901090',
                '971545731657'
            ],
            'address' => 'SA',
            'description' => 'My Dad',
        ]);
    }

    public function seedPhones(): void
    {
        $phones = [
            // iPhone X
            ['model' => 'iPhone X', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB256], 'colors' => [PhoneColors::Black, PhoneColors::Silver, PhoneColors::SpaceGray], 'description' => 'The first iPhone with an OLED display and Face ID.'],
            ['model' => 'iPhone XS', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB256], 'colors' => [PhoneColors::Black, PhoneColors::Silver, PhoneColors::SpaceGray], 'description' => 'The first iPhone with an OLED display and Face ID.'],
            ['model' => 'iPhone XS MAX', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Black, PhoneColors::Silver, PhoneColors::SpaceGray], 'description' => 'The first iPhone with an OLED display and Face ID.'],

            // iPhone 11 Series
            ['model' => 'iPhone 11', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB128, PhoneStorages::GB256], 'colors' => [PhoneColors::Black, PhoneColors::White, PhoneColors::Green, PhoneColors::Yellow, PhoneColors::Purple, PhoneColors::Red], 'description' => 'Dual-camera system with Night Mode.'],
            ['model' => 'iPhone 11 Pro', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::MidnightGreen, PhoneColors::SpaceGray, PhoneColors::Silver, PhoneColors::Gold], 'description' => 'Triple-camera system with Night Mode.'],
            ['model' => 'iPhone 11 Pro Max', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::MidnightGreen, PhoneColors::SpaceGray, PhoneColors::Silver, PhoneColors::Gold], 'description' => 'Largest display and battery in the iPhone 11 series.'],

            // iPhone 12 Series
            ['model' => 'iPhone 12', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB128, PhoneStorages::GB256], 'colors' => [PhoneColors::Black, PhoneColors::White, PhoneColors::Blue, PhoneColors::Green, PhoneColors::Red], 'description' => 'First iPhone with 5G support and Ceramic Shield.'],
            ['model' => 'iPhone 12 Mini', 'storages' => [PhoneStorages::GB64, PhoneStorages::GB128, PhoneStorages::GB256], 'colors' => [PhoneColors::Black, PhoneColors::White, PhoneColors::Blue, PhoneColors::Green, PhoneColors::Red], 'description' => 'Compact design with 5G support.'],
            ['model' => 'iPhone 12 Pro', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Graphite, PhoneColors::Silver, PhoneColors::Gold, PhoneColors::PacificBlue], 'description' => 'Triple-camera system with LiDAR scanner.'],
            ['model' => 'iPhone 12 Pro Max', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Graphite, PhoneColors::Silver, PhoneColors::Gold, PhoneColors::PacificBlue], 'description' => 'Largest display and battery in the iPhone 12 series.'],

            // iPhone 13 Series
            ['model' => 'iPhone 13', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Pink, PhoneColors::Blue, PhoneColors::Midnight, PhoneColors::Starlight, PhoneColors::Red, PhoneColors::Green], 'description' => 'Improved battery life and A15 Bionic chip.'],
            ['model' => 'iPhone 13 Mini', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Pink, PhoneColors::Blue, PhoneColors::Midnight, PhoneColors::Starlight, PhoneColors::Red, PhoneColors::Green], 'description' => 'Compact design with A15 Bionic chip.'],
            ['model' => 'iPhone 13 Pro', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::Graphite, PhoneColors::Gold, PhoneColors::Silver, PhoneColors::SierraBlue], 'description' => 'Triple-camera system with ProMotion display.'],
            ['model' => 'iPhone 13 Pro Max', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::Graphite, PhoneColors::Gold, PhoneColors::Silver, PhoneColors::SierraBlue], 'description' => 'Largest display and battery in the iPhone 13 series.'],

            // iPhone 14 Series
            ['model' => 'iPhone 14', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Midnight, PhoneColors::Starlight, PhoneColors::Red, PhoneColors::Blue, PhoneColors::Purple, PhoneColors::Yellow], 'description' => 'Emergency SOS via satellite and Car Crash Detection.'],
            ['model' => 'iPhone 14 Plus', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Midnight, PhoneColors::Starlight, PhoneColors::Red, PhoneColors::Blue, PhoneColors::Purple, PhoneColors::Yellow], 'description' => 'Larger display and battery than iPhone 14.'],
            ['model' => 'iPhone 14 Pro', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::SpaceBlack, PhoneColors::Silver, PhoneColors::Gold, PhoneColors::DeepPurple], 'description' => 'Dynamic Island and Always-On display.'],
            ['model' => 'iPhone 14 Pro Max', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::SpaceBlack, PhoneColors::Silver, PhoneColors::Gold, PhoneColors::DeepPurple], 'description' => 'Largest display and battery in the iPhone 14 series.'],

            // iPhone 15 Series
            ['model' => 'iPhone 15', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Black, PhoneColors::Blue, PhoneColors::Green, PhoneColors::Yellow, PhoneColors::Pink], 'description' => 'USB-C port and Dynamic Island.'],
            ['model' => 'iPhone 15 Plus', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::Black, PhoneColors::Blue, PhoneColors::Green, PhoneColors::Yellow, PhoneColors::Pink], 'description' => 'Larger display and battery than iPhone 15.'],
            ['model' => 'iPhone 15 Pro', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::BlueTitanium], 'description' => 'USB-C port and Dynamic Island.'],
            ['model' => 'iPhone 15 Pro Max', 'storages' => [PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::BlueTitanium], 'description' => 'USB-C port and Dynamic Island.'],

            // iPhone 16 Series
            ['model' => 'iPhone 16', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::DesertTitanium], 'description' => 'A18 chip and improved camera system.'],
            ['model' => 'iPhone 16 Plus', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::DesertTitanium], 'description' => 'Larger display and battery than iPhone 16.'],
            ['model' => 'iPhone 16 Pro', 'storages' => [PhoneStorages::GB128, PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::DesertTitanium], 'description' => 'A18 chip and improved camera system.'],
            ['model' => 'iPhone 16 Pro Max', 'storages' => [PhoneStorages::GB256, PhoneStorages::GB512, PhoneStorages::TB1], 'colors' => [PhoneColors::BlackTitanium, PhoneColors::WhiteTitanium, PhoneColors::NaturalTitanium, PhoneColors::DesertTitanium], 'description' => 'A18 chip and improved camera system.'],
        ];

        foreach ($phones as $index => $phone) {
            PhoneModel::create([
                'model' => $phone['model'],
                'storages' => array_map(fn($storage) => $storage->value, $phone['storages']),
                'colors' => array_map(fn($color) => $color->value, $phone['colors']),
                'description' => $phone['description'],
                'created_at' => now()->addMinutes($index+1)
            ]);
        }
    }
}
