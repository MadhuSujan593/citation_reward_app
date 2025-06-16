<?php

namespace Database\Factories;

use App\Models\PublishedPaper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublishedPaperFactory extends Factory
{
    protected $model = PublishedPaper::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);
        $author = $this->faker->name();
        $year = $this->faker->year();
        $publisher = $this->faker->company();
        $location = $this->faker->city();

        return [
            'title' => $title,
            'mla' => "$author. \"$title.\" *$publisher*, $year.",
            'apa' => "$author. ($year). *$title*. $publisher.",
            'chicago' => "$author. *$title*. $location: $publisher, $year.",
            'harvard' => "$author ($year), *$title*, $publisher, $location.",
            'vancouver' => "$author. $title. $publisher; $year.",
            'doi' => '10.' . $this->faker->numberBetween(1000, 9999) . '/' . $this->faker->bothify('????###'),
            'user_id' => User::factory(), // Create a new user for each paper
        ];
    }
}
