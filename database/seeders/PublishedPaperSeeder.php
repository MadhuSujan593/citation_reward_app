<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PublishedPaper;

class PublishedPaperSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->has(PublishedPaper::factory()->count(10))
            ->create();
    }
}
