<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::create([
            'title' => 'Sample Article',
            'author' => 'John Doe',
            'description' => 'This is a sample article description.',
            'content' => 'This is the full content of the sample article.',
            'url' => 'https://example.com/sample-article',
            'image_url' => 'https://via.placeholder.com/150',
            'source' => 'Example Source',
            'category' => 'General',
            'published_at' => now(),
        ]);
    }
}
