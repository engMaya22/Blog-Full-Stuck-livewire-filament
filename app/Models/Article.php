<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
   
    public function shortBody($words = 30): string
    {
        return Str::words(strip_tags($this->body), $words);
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

    public function humanReadTime(): Attribute//human_read_time
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $words = Str::wordCount(strip_tags($attributes['content']));
                $minutes = ceil($words / 200);

                return $minutes . ' ' . str('min')->plural($minutes) . ', '
                    . $words . ' ' . str('word')->plural($words);
            }
        );
    }
}
            