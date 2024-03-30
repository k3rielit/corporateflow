<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@test.com'],
            ['name' => 'User', 'email' => 'user@test.com'],
        ];
        User::factory()->count(count($users))->state(new Sequence(...$users))->create();
    }

}
