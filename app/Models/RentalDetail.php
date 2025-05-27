<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalDetail extends Model
{
    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rental_id',
        'item_id',
        'quantity',
        'is_returned',
        'sub_total',
    ];

    /**
     * Attribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rental_id' => 'integer',
            'item_id' => 'integer',
            'quantity' => 'integer',
            'is_returned' => 'boolean',
            'sub_total' => 'decimal:2',
        ];
    }
    /**
     * Relasi dengan model Rental dan Item.
     *
     * @var list<string>
     * @see \App\Models\Rental
     * @see \App\Models\Item
     */
    protected $with = ['rental', 'item'];

    /**
     * Mengambil detail rental yang terkait dengan item ini.
     *
     * @return BelongsTo<\Database\Eloquent\Relations\BelongsTo>
     * @see \App\Models\Rental
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Mengambil detail item yang terkait dengan item ini.
     *
     * @return BelongsTo<\Database\Eloquent\Relations\BelongsTo>
     * @see \App\Models\Item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
