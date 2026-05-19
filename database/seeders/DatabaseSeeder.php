<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua class seeder di sini
        $this->call([
            UserSeeder::class,
            // Nanti seeder lain seperti ArmadaSeeder, RuteSeeder bisa ditambah di bawahnya
        ]);
    }
}