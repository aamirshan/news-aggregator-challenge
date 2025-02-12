<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $articles = $query->latest('published_at')->paginate(10);

        return view('articles.index', compact('articles'));
    }

    public function getArticles(Request $request)
    {
        $query = Article::query();

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Retrieve articles with pagination
        $articles = $query->latest('published_at')->paginate(10);

        // Return JSON response
        return response()->json([
            'data' => $articles->items(), // Paginated data
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }
}
