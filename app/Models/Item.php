<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'stock',
        'category_id',
        'is_available',
        'image',
        'sewa',
    ];

    protected $with = ['category'];

    public function rentaldetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when(
                $filters['search'] ?? false,
                fn($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
            );
        $query->when(
            $filters['category'] ?? false,
            function ($query, $category) {
                if (is_array($category)) {
                    $query->whereHas(
                        'category',
                        fn($query) =>
                        $query->whereIn('slug', $category)
                    );
                } else {
                    $query->whereHas(
                        'category',
                        fn($query) =>
                        $query->where('slug', $category)
                    );
                }
            }
        );
        $query->when(
            $filters['name'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('name');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('name');
                } else {
                    $query->latest();
                }
            }
        );
        $query->when(
            $filters['status'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('status');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('status');
                } else {
                    $query->latest();
                }
            }
        );
        $query->when(
            $filters['stock'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('stock');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('stock');
                } else {
                    $query->latest();
                }
            }
        );
        $query->when(
            $filters['price'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('sewa');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('sewa');
                } else {
                    $query->latest();
                }
            }
        );
        $query->when(
            $filters['sort'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('sewa');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('sewa');
                } else {
                    $query->latest();
                }
            }
        );
    }
}
