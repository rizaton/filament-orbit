<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class RentalDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_rental',
        'id_item',
        'quantity',
        'is_returned',
        'sub_total'
    ];
    protected $primaryKey = 'id_rental_detail';
    protected $casts = [
        'id_rental' => 'integer',
        'id_item' => 'integer',
        'quantity' => 'integer',
        'is_returned' => 'boolean',
        'sub_total' => 'decimal:2',
    ];
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class, 'id_rental', 'id_rental');
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
    protected static function booted()
    {
        static::creating(function (RentalDetail $rentalDetail) {
            $rentalDetail->loadMissing('item');
            $price = (float) $rentalDetail->item->rent_price;
            $qty = (int) $rentalDetail->quantity;
            if ($price <= 0 || $qty <= 0) {
                Log::error('Invalid rental detail values', [
                    'price' => $price,
                    'quantity' => $qty,
                    'item_id' => $rentalDetail->id_item,
                ]);
                throw new \InvalidArgumentException('Price and quantity must be greater than 0.');
            }
            $rentalDetail->sub_total = $price * $qty;
        });
        static::created(function (RentalDetail $rentalDetail) {
            $rentalDetail->rental->recalculateTotals();
        });
        static::updating(function (RentalDetail $rentalDetail) {
            if ($rentalDetail->isDirty('quantity')) {
                DB::transaction(function () use ($rentalDetail) {
                    $rentalDetail->loadMissing('item');
                    $rentalDetail->sub_total = $rentalDetail->item->rent_price * $rentalDetail->quantity;
                    $rentalDetail->saveQuietly();
                    $rentalDetail->rental->recalculateTotals();
                });
            }
        });
    }
    protected static function calculateDownPayment(float $totalFees, string $city): float
    {
        try {
            return match (true) {
                $city === 'Luar Jabodetabek' => $totalFees,
                $totalFees > 2000000 => $totalFees * 0.5,
                default => $totalFees * 0.25,
            };
        } catch (\Throwable $th) {
            Log::error('Error calculating down payment', [
                'total_fees' => $totalFees,
                'city' => $city,
                'error' => $th->getMessage(),
                'timestamp' => now(),
            ]);
            return 0.0;
        }
    }
    public function recalculateSubtotal(): void
    {
        $this->loadMissing('item');
        $price = (float) $this->item->rent_price;
        $qty = (int) $this->quantity;
        if ($price <= 0) {
            Log::error('Harga item tidak boleh <= 0.', [
                'item_id' => $this->id_item,
                'rental_detail_id' => $this->id_rental_detail,
                'timestamp' => now(),
            ]);
            throw new \InvalidArgumentException('Harga item tidak boleh <= 0.');
        }
        if ($qty <= 0) {
            Log::error('Jumlah sewa tidak boleh <= 0.', [
                'item_id' => $this->id_item,
                'rental_detail_id' => $this->id_rental_detail,
                'timestamp' => now(),
            ]);
        }
        $this->sub_total = $price * $qty;
        $this->save();
    }
}
