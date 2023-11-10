<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::whereDate('published_at','<',now())
                           ->whereActive(true)
                           ->orderBy('published_at','desc')
                           ->paginate(10);
        return view('home',compact('articles'));
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
