<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
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
        $data['name'] = 'nevergonnagiveyouup';
        $data['gender'] = 'male';
        $data['birthdate'] = '1999-10-21';
        $data['addressId'] = 'never gonna let u down';
        $data['civilId'] = '54321';
        $data['taxId'] = '211296431';
        $data['contactNumber'] = '938053888';
        $data['email'] = 'nvrturnaround@gmail.com';
        $data['remember_token'] = Str::random(50);
        $data['password'] = bcrypt(123456);
        
        \App\Models\Owner::create($data);
    }
}
