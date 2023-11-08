<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->realText(50);
        $categoryId = Category::first()->id;
        return [
            'title' => $title,
            'image' => fake()->imageUrl,
            'content' => fake()->realText(5000),
            'active' => fake()->boolean,
            'published_at' => fake()->dateTime,
            'author_id' => 1 ,
            'category_id' => $categoryId,
        ];
    }
}
