<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //\App\Models\User::factory(1)->create();

        \App\Models\User::factory()->create([
            'name' => 'João Paulo',
            'email' => 'teste@teste.com',
            'password' => Hash::make('123'),
        ]);

        \App\Models\Categoria::factory(10)->create();
        \App\Models\Produto::factory(30)->create();
    }
}
