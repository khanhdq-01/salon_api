<?php

namespace Database\Seeders;

use App\Models\HairstyleArticle;
use Database\Seeders\Data\DemoHairstyleArticlesData;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class HairstyleArticleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);

            $articles = DemoHairstyleArticlesData::articlesForSalon($index);

            foreach ($articles as $order => $article) {
                HairstyleArticle::query()->create([
                    'salon_id' => $salon->id,
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'image' => $article['image'],
                    'category' => $article['category'],
                    'order' => $order,
                    'is_active' => true,
                ]);
            }
        }
    }
}
