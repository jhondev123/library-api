<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Fine;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::create([
            'name' => 'Jhonattan',
            'email' => 'jhonattan@gmail.com',
            'password' => bcrypt('123456')
            ]);

        Book::factory(30)->create();
        $loans = \App\Models\Loan::factory(30)->create();
        $loans->each(function($loan){
            if($loan->delivery_status === 'late') {
                Fine::factory(1)->create([
                    'loan_id' => $loan->id,
                    'value' => 10
                ]);
            }

        });
    }
}
