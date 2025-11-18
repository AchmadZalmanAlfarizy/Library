<?php
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $borrowDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowDate)->modify('+14 days');
        $returned = $this->faker->boolean();

        return [
            'member_id' => Member::inRandomOrder()->first()?->id ?? Member::factory(),
            'book_id' => Book::inRandomOrder()->first()?->id ?? Book::factory(),
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'return_date' => $returned ? $this->faker->dateTimeBetween($borrowDate, $dueDate) : null,
            'fine_amount' => $returned ? $this->faker->numberBetween(0, 50) : 0,
        ];
    }
}
