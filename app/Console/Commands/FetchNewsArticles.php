<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use Carbon\Carbon;

class FetchNewsArticles extends Command
{
    protected $signature = 'fetch:news';
    protected $description = 'Fetch news from APIs and store them in the database';

    public function handle()
    {
        $this->fetchNewsAPI();
        $this->fetchNYT();
        $this->fetchGuardian();
    }

    private function fetchNewsAPI()
    {
        $response = Http::retry(
            env('NEWSAPI_RETRY_TIMES', 3), // Number of retries
            env('NEWSAPI_RETRY_SLEEP', 2)  // Delay between retries
        )->timeout(env('NEWSAPI_TIMEOUT', 15))->get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => env('NEWSAPI_KEY'),
            'country' => 'us',
        ]);

        if ($response->successful()) {
            $articles = $response->json('articles') ?? [];
            $this->saveArticles($articles, 'NewsAPI');
        } else {
            $this->error('Failed to fetch articles from NewsAPI');
        }
    }

    private function fetchNYT()
    {
        $response = Http::retry(
            env('NEWYORKTIMESAPI_RETRY_TIMES', 3), 
            env('NEWYORKTIMESAPI_RETRY_SLEEP', 2)
        )->timeout(env('NEWYORKTIMESAPI_TIMEOUT', 15))->get('https://api.nytimes.com/svc/topstories/v2/home.json', [
            'api-key' => env('NEWYORKTIMESAPI_KEY'),
        ]);

        if ($response->successful()) {
            $articles = $response->json('results') ?? [];
            $this->saveArticles($articles, 'New York Times');
        } else {
            $this->error('Failed to fetch articles from New York Times');
        }
    }

    private function fetchGuardian()
    {
        $response = Http::retry(
            env('GUARDIANAPI_RETRY_TIMES', 3), 
            env('GUARDIANAPI_RETRY_SLEEP', 2)
        )->timeout(env('GUARDIANAPI_TIMEOUT', 15))->get('https://content.guardianapis.com/search', [
            'api-key' => env('GUARDIANAPI_KEY'),
            'show-fields' => 'all',
        ]);

        if ($response->successful()) {
            $articles = $response->json('response.results') ?? [];
            $this->saveArticles($articles, 'The Guardian');
        } else {
            $this->error('Failed to fetch articles from The Guardian');
        }
    }

    private function saveArticles($articles, $source)
    {
        if (empty($articles)) {
            $this->info("No articles found for source: {$source}");
            return;
        }

        foreach ($articles as $article) {
            $publishedAt = isset($article['publishedAt']) 
                ? Carbon::parse($article['publishedAt'])->toDateTimeString()
                : (isset($article['webPublicationDate']) 
                    ? Carbon::parse($article['webPublicationDate'])->toDateTimeString() 
                    : null);

            Article::updateOrCreate(
                ['url' => $article['url'] ?? $article['webUrl'] ?? null],
                [
                    'title' => $article['title'] ?? $article['webTitle'] ?? 'Untitled',
                    'description' => $article['description'] ?? $article['fields']['trailText'] ?? null,
                    'url' => $article['url'] ?? $article['webUrl'] ?? null,
                    'image_url' => $article['urlToImage'] ?? $article['fields']['thumbnail'] ?? null,
                    'source' => $source,
                    'published_at' => $publishedAt,
                ]
            );
        }

        $this->info("Successfully saved articles from {$source}");
    }
}
