<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];

    protected static function booted(): void
    {
        static::saved(fn (Category $category) => static::clearUserCategoriesCache($category->user_id));
        static::deleted(fn (Category $category) => static::clearUserCategoriesCache($category->user_id));
    }

    public static function clearUserCategoriesCache(?int $userId): void
    {
        if ($userId !== null) {
            Cache::forget('categories.user.'.$userId);
        }
    }

    public static function getCachedForUser(int $userId)
    {
        return Cache::remember('categories.user.'.$userId, 300, fn () => static::where('user_id', $userId)->orderBy('name')->get());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
