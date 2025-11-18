<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        Loan::create([
            'member_id' => 1,
            'book_id' => 1,
            'borrow_date' => now()->subDays(7),
            'due_date' => now()->addDays(7),
            'return_date' => null,
            'fine_amount' => 0,
        ]);

        Loan::create([
            'member_id' => 2,
            'book_id' => 2,
            'borrow_date' => now()->subDays(10),
            'due_date' => now()->subDays(2),
            'return_date' => now()->subDay(),
            'fine_amount' => 5,
        ]);
    }
}
