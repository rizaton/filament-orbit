<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'color',
    ];

    protected $primaryKey = 'id_category';

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'slug' => 'string',
            'color' => 'string',
        ];
    }

    /**
     * Mengambil semua item yang terkait dengan kategori ini.
     *
     * @return HasMany<\Database\Eloquent\Relations\HasMany>
     * @see \App\Models\Item
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'id_category');
    }
}
