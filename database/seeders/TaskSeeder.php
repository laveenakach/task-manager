<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $titles = ['Buy groceries', 'Finish report', 'Call Mike', 'Plan weekend'];
        foreach ($titles as $index => $title) {
            Task::create([
                'title' => $title,
                'description' => 'Sample description for ' . $title,
                'position' => $index + 1,
                'completed' => false,
            ]);
        }
    }
}
