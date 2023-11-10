<?php

namespace App\View\Components;

use App\Models\Article;
use App\Models\UpvoteDownvote as ModelsUpvoteDownvote;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UpvoteDownvote extends Component
{
    public Article $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }
  
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

  

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $upvotes = ModelsUpvoteDownvote::where('article_id', '=', $this->article->id)
        ->whereIsUpvote(true)
        ->count();

        $downvotes = ModelsUpvoteDownvote::where('article_id', '=', $this->article->id)
        ->whereIsUpvote(false)
        ->count();
        // This will be null, true, or false
        // null means user has not done upvote or downvote
        $hasUpvote = null;

        /** @var \App\Models\User $user */
        $user = request()->user();
        if ($user) {
            $model = ModelsUpvoteDownvote::where('article_id', '=', $this->article->id)->where('user_id', '=', $user->id)->first();
            if ($model) {
                $hasUpvote = !!$model->is_upvote;
            }
        }
        return view('components.upvote-downvote',compact('upvotes','downvotes','hasUpvote'));
    }

    public function upvoteDownvote($upvote = true)
    {
        /** @var \App\Models\User $user */
        $user = request()->user();//current user
        if (!$user) {
            return $this->redirect('login');
        }

        $model = ModelsUpvoteDownvote::where('article_id', '=', $this->article->id)->where('user_id', '=', $user->id)->first();

        if (!$model) {
            ModelsUpvoteDownvote::create([
                'is_upvote' => $upvote,
                'article_id' => $this->article->id,
                'user_id' => $user->id
            ]);

            return;//to stop excute
        }

        if ($upvote && $model->is_upvote || !$upvote && !$model->is_upvote) {
            $model->delete();
        } else {
            $model->is_upvote = $upvote;
            $model->save();
        }
    }
}
