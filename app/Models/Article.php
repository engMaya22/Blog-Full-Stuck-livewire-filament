<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'published_at',
        'active',
        'image',
        'meta_title',
        'meta_description',
        'author_id',
        'category_id'
    ];
   
    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function shortBody()
    {
         return  substr($this->content, 0, 100);

    }

    public function getFormattedDate(){
        return $this->published_at->format('F jS Y');
    }
    public function getImage()
    {
        if (str_starts_with($this->image, 'http')) {
            return $this->image;//from faker
        }

        return '/storage/' . $this->image;
    }
}
            