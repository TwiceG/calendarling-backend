<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    public function run(): void
    {
        $notes =
            [
                ['date' => '2024-11-01', 'note' => 'Something happening here!'],
                ['date' => '2024-11-02', 'note' => 'Something happening here!'],
                ['date' => '2024-11-05', 'note' => 'Something happening here!']
            ];

        DB::table('notes')->insert($notes);
    }
}
