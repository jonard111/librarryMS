<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed sample overdue reservations for testing fine calculation
        $this->call([
            DemoOverdueReservationSeeder::class,
        ]);
    }
}
