<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publishedYear',
        'available',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function getReviewCountAttribute(): int
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }
        return $this->reviews()->count();
    }
    public function getAverageRatingAttribute(): ?float
    {
        $averageRating = $this->relationLoaded('reviews') 
        ? $this->reviews->avg('rating') 
        : $this->reviews()->avg('rating');
        return $averageRating !== null 
        ? round((float) $averageRating, 2) 
        : null;
    }

}
