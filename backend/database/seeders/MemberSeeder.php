<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        Member::create([
            'name' => 'John Doe',
            'member_id' => 'M001',
            'email' => 'john@example.com',
            'phone_number' => '123456789',
            'address' => '123 Main St',
        ]);

        Member::create([
            'name' => 'Jane Smith',
            'member_id' => 'M002',
            'email' => 'jane@example.com',
            'phone_number' => '987654321',
            'address' => '456 Elm St',
        ]);
    }
}
