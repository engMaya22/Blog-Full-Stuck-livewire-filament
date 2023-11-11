<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        // Latest post
        $latestArticle = Article::whereActive(true)
        ->whereDate('published_at', '<', now())
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->first();

        // Show the most popular posts based on upvotes
        $popularArticles  = Article::query()
            ->leftJoin('upvote_downvotes', 'articles.id', '=', 'upvote_downvotes.article_id')
            ->select('articles.*', DB::raw('COUNT(upvote_downvotes.id) as upvote_count'))
            ->where(function ($query) {
                $query->whereNull('upvote_downvotes.is_upvote')//not record for article
                    ->orWhere('upvote_downvotes.is_upvote', '=', 1);
            })
            ->whereActive(true)
            ->whereDate('published_at', '<', now())
            ->orderByDesc('upvote_count')
            ->groupBy([
                'articles.id',
                'articles.title',
                'articles.image',
                'articles.content',
                'articles.active',
                'articles.published_at',
                'articles.author_id',
                'articles.created_at',
                'articles.updated_at',
                'articles.meta_title',
                'articles.meta_description',
            ])
            ->limit(5)
            ->get();

        // If authorized - Show recommended posts based on user upvotes
        $user = auth()->user();

        if ($user) {
               
            $leftJoin = "(SELECT c.id as category_id, a.id as article_id FROM upvote_downvotes
                          JOIN articles a ON upvote_downvotes.article_id = a.id
                          JOIN categories c ON c.id = a.category_id
                          WHERE upvote_downvotes.is_upvote = 1 and upvote_downvotes.user_id = ?) as t";

            $recommendedArticles = Article::query()
            ->leftJoin('categories as c', 'articles.category_id', '=', 'c.id')
            ->leftJoin(DB::raw($leftJoin), function ($join) {
            $join->on('t.category_id', '=', 'c.id')
                ->on('t.article_id', '<>', 'articles.id');
            })
            ->select('articles.*')
            ->where('articles.id', '<>', DB::raw('t.article_id'))//filter to exclude articles that the user has already upvoted.
            ->setBindings([$user->id])
            ->limit(3)
            ->get();

          } // Not authorized - Popular posts based on views
        else {
            $recommendedArticles = Article::query()
                ->leftJoin('article_views', 'articles.id', '=', 'article_views.article_id')
                ->select('articles.*', DB::raw('COUNT(article_views.id) as view_count'))
                ->where('articles.active', true)
                ->whereDate('articles.published_at', '<', now())
                ->groupBy('articles.id', 'articles.title', 'articles.content')
                ->orderByDesc('view_count')
                ->limit(3)
                ->get();

                
         }


       // Show recent categories with their latest posts

        $categories = Category::query()
                   ->whereHas('articles', function ($query) {
                       $query
                           ->where('active', true)
                           ->whereDate('published_at', '<', now());
                   })
                   ->select('categories.*')
                   ->selectRaw('MAX(articles.published_at) as max_date')
                   ->leftJoin('articles', 'articles.category_id', '=', 'categories.id')
                   ->orderByDesc('max_date')
                   ->groupBy('categories.id')
                   ->take(5)
                   ->get();

        return view('home', compact(
            'latestArticle',
            'popularArticles',
            'recommendedArticles',
             'categories'
        ));
    }

  
    /**
     * Display the specified resource.
     */
    public function show(Article $article , Request $request)//we add request to catch auth user data && request data
    {
        if (!$article->active || $article->published_at > now()) {
            throw new NotFoundHttpException();
        }
        $next = Article::whereActive(true)
        ->whereDate('published_at', '<=', now())
        ->whereDate('published_at', '<', $article->published_at)
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->first();

       $prev = Article::whereActive(true)
        ->whereDate('published_at', '<=',now())
        ->whereDate('published_at', '>', $article->published_at)
        ->orderBy('published_at', 'asc')
        ->limit(1)
        ->first();

        $user = $request->user();
        // dd( Auth::user());
        ArticleView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'article_id' => $article->id,
            'user_id' => $user?->id//if user hasnot login
        ]);

        return view('article.view',compact('article','next','prev'));

    }

    public function byCategory(Category $category)
    {
        // dd($category);
        $articles = $category->articles()
            ->where('active', '=', true)
            ->whereDate('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('article.index', compact('articles','category'));
    }

  
}
