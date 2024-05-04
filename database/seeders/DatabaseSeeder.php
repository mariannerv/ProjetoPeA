<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use App\Models\PoliceStation;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $data['morada'] = 'Largo da Penha de França, nº 1';
        $data['codigo_postal'] = '1199-010';
        $data['localidade'] = 'Lisboa';
        $data['unidade'] = '938053888';
        $data['telefone'] = '218111000';
        $data['fax'] = '219020019';
        $data['email'] = 'contacto@psp.pt';
        
        \App\Models\PoliceStation::create($data);
    }
}
