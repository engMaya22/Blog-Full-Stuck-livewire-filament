<?php

namespace App\Filament\Widgets;

use App\Models\ArticleView;
use App\Models\UpvoteDownvote;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class ArticleOverview extends Widget
{
    //check widget component class functions
    protected int | string | array $columnSpan = 3;//we add
    public ?Model $record = null;//to access to record id

// php artisan make:filament-widget ArticleOverview 
    protected function getViewData(): array//we override this method to customize data of 
    // cuze we added this widget in view of each article  so we access to id of article by $this ->record->id, 
    {
        return [
            'viewCount' => ArticleView::where('article_id', '=', $this->record->id)->count(),
            'upvotes' => UpvoteDownvote::where('article_id', '=', $this->record->id)->where('is_upvote', '=', 1)->count(),
            'downvotes' => UpvoteDownvote::where('article_id', '=', $this->record->id)->where('is_upvote', '=', 0)->count(),
        ];
    }

    protected static string $view = 'filament.widgets.article-overview';//this article-overview created for this widget and we will display the data
}
