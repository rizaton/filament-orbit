<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'color',
    ];
    protected $primaryKey = 'id_category';
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'slug' => 'string',
            'color' => 'string',
        ];
    }
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'id_category');
    }
}
