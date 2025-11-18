<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'publisher' => 'Scribner',
            'category_id' => 1, // Assuming category with ID 1 exists
            'stock' => 10,
            'book_cover' => 'great_gatsby.jpg',
        ]);

        Book::create([
            'title' => 'A Brief History of Time',
            'author' => 'Stephen Hawking',
            'publisher' => 'Bantam',
            'category_id' => 3,
            'stock' => 5,
            'book_cover' => 'history_of_time.jpg',
        ]);
    }
}
