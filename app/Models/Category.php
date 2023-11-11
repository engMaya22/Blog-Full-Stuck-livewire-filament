<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];


    public function articles(){
        return $this->hasMany(Article::class);
    }
    public function publishedArticles()
    {
        return $this->belongsTo(Article::class)
            ->whereActive(true)
            ->whereDate('published_at', '<',now());
    }
   
}
