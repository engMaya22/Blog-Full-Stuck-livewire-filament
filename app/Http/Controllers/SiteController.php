<?php

namespace App\Http\Controllers;

use App\Models\TextWidget;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function about()
    {
        $widget = TextWidget::query()
            ->where('key', '=', 'about-page')
            ->whereActive(true)
            ->first();

        return view('about', compact('widget'));
    }
}
