<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Natalia Erira',
            'email' => 'natalia.erira@agencia.com',
            'password' => bcrypt('1234'),
        ]);

        User::factory()->count(9)->create();
    }
}
