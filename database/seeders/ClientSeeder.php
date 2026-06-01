<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->count(100)->create()->each(function ($client) {

            $contacts = Contact::factory()->count(5)->create(['client_id'  => $client->id]);

            $contacts->first()->update(['is_primary' => true]);
        });
    }
}
