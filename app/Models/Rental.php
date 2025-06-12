<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    protected $guarded = [];
    /**
     * Atribut yang dapat diisi secara massal.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'status',
        'down_payment',
        'rent_date',
        'return_date',
        'late_date',
        'late_fees',
        'total_fees',
    ];


    protected $primaryKey = 'id_rental';

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_user' => 'integer',
            'status' => 'string',
            'down_payment' => 'decimal:2',
            'rent_date' => 'date',
            'return_date' => 'date',
            'late_date' => 'date',
            'late_fees' => 'decimal:2',
            'total_fees' => 'decimal:2'
        ];
    }

    /**
     * Mengambil data pengguna yang memproses sewa.
     *
     * @return BelongsTo<\Database\Eloquent\Relations\BelongsTo>
     * @see \App\Models\User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Mengambil semua detail penyewaan yang terkait dengan sewa ini.
     *
     * @return HasMany<\Database\Eloquent\Relations\HasMany>
     * @see \App\Models\RentalDetail
     */
    public function rentalDetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class, 'id_rental', 'id_rental');
    }

    protected static function booted()
    {

        static::updated(function (Rental $rental) {
            if (!$rental->wasChanged('status')) {
                return;
            }

            foreach ($rental->rentalDetails as $detail) {
                $item = $detail->item;

                if (!$item || $detail->is_returned) {
                    continue;
                }

                if ($rental->status === 'approved') {
                    if ($item->is_available) {
                        $item->decrement('stock', $detail->quantity);
                        $item->update(['is_available' => $item->stock > 0]);
                    }
                } elseif ($rental->status === 'returned' && !$detail->is_returned) {
                    $item->increment('stock', $detail->quantity);
                    RentalDetail::where('rental_id', $rental->id)->update(['is_returned' => true]);
                } elseif ($rental->isDirty('status') && $rental->status === 'pending' && $rental->original('status') === 'approved') {
                    $item->increment('stock', $detail->quantity);
                    $item->update(['is_available' => true]);
                    RentalDetail::where('rental_id', $rental->id)->update(['is_returned' => false]);
                }
            }
        });
    }
}
