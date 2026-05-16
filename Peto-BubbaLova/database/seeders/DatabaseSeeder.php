<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //llamar a los seeders
        $this->call(RoleSeeder::class);
        
        //usuario de prueba
        $admin = User::create([
            'name' => 'Adrian Admin',
            'email' => 'adrianyamq@gmail.com',
            'password' => bcrypt('password')
        ]);

        $admin->assignRole('Administrador');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
