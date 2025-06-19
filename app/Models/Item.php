<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'stock',
        'rent_price',
        'description',
        'id_category',
        'is_available',
        'image',
    ];

    protected $primaryKey = 'id_item';

    /**
     * Relasi dengan model Category.
     *
     * @var list<string>
     * @see \App\Models\Category
     */
    protected $with = ['category'];

    /**
     * Mengambil semua detail penyewaan yang terkait dengan item ini.
     *
     * @return HasMany<\Database\Eloquent\Relations\HasMany>
     * @see \App\Models\RentalDetail
     */
    public function rentaldetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'slug' => 'string',
            'name' => 'string',
            'stock' => 'integer',
            'rent_price' => 'decimal:2',
            'description' => 'string',
            'category_id' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Mengambil detail kategori yang terkait dengan item ini.
     *
     * @return BelongsTo<\Database\Eloquent\Relations\BelongsTo>
     * @see \App\Models\Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    /**
     * Scope untuk memfilter item berdasarkan kriteria tertentu.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
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
            $filters['sort'] ?? false,
            function ($query, $sort) {
                if ($sort === 'highest') {
                    $query->orderByDesc('rent_price');
                } elseif ($sort === 'lowest') {
                    $query->orderBy('rent_price');
                } else {
                    $query->latest();
                }
            }
        );
    }

    /**
     * Method "booted" untuk menangani event model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(function (Item $item) {
            if ($item->isDirty('rent_price')) {
                $item->loadMissing('rentaldetails.rental');
                foreach ($item->rentaldetails as $detail) {
                    if (!$detail->rental || $detail->is_returned) {
                        continue;
                    }
                    DB::transaction(function () use ($detail, $item) {
                        $detail->sub_total = $detail->quantity * $item->rent_price;

                        $detail->saveQuietly();
                    });
                }
            }
        });
    }
}
