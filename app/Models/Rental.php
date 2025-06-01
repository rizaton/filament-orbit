<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    /**
     * Atribut yang dapat diisi secara massal.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'performed_by',
        'name',
        'address',
        'phone',
        'status',
        'down_payment',
        'rent_date',
        'return_date',
        'late_date',
        'late_fees',
        'total_fees'
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'performed_by' => 'integer',
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string',
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
        return $this->belongsTo(User::class);
    }

    /**
     * Mengambil semua detail penyewaan yang terkait dengan sewa ini.
     *
     * @return HasMany<\Database\Eloquent\Relations\HasMany>
     * @see \App\Models\RentalDetail
     */
    public function rentaldetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    protected static function booted()
    {
        static::updated(function (Rental $rental) {
            if (!$rental->wasChanged('status')) {
                return;
            }

            foreach ($rental->rentalDetails as $detail) {
                $item = $detail->item;

                if (!$item) {
                    continue;
                }

                if ($rental->status === 'approved') {
                    if ($item->is_available) {
                        $item->decrement('stock', $detail->quantity);
                        $item->update(['is_available' => $item->stock > 0]);
                    }
                } elseif ($rental->status === 'returned') {
                    $item->increment('stock', $detail->quantity);
                    RentalDetail::where('rental_id', $rental->id)->update(['is_returned' => true]);
                }
            }
        });
    }
}
