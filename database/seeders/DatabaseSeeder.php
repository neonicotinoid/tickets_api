<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
         ]);

         Ticket::factory(['created_at' => now()])->count(3)->create();
         Ticket::factory(['created_at' => now()->subDays(3)])->count(3)->create();
         Ticket::factory(5)->resolved()->create();
    }
}
