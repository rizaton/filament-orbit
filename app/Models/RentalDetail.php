<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Rental;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

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
        return $this->belongsTo(Rental::class, 'id_rental');
    }

    /**
     * Mengambil detail item yang terkait dengan item ini.
     *
     * @return BelongsTo<\Database\Eloquent\Relations\BelongsTo>
     * @see \App\Models\Item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    /**
     * method "booted" untuk menangani event model.
     * 
     * @return void
     * @see \Illuminate\Database\Eloquent\Model::booted()
     * @see \Illuminate\Database\Eloquent\Model::updating()
     * @see \Illuminate\Database\Eloquent\Model::updated()
     */
    protected static function booted()
    {
        static::updating(function (RentalDetail $rentalDetail) {

            // Jika quantity berubah, hitung ulang sub_total
            // dan update total_fees pada rental terkait
            // serta down_payment berdasarkan total_fees

            if ($rentalDetail->isDirty('quantity')) {
                $previousSubTotal = $rentalDetail->getOriginal('sub_total');

                $rentalDetail->loadMissing('item');
                $price = (float) $rentalDetail->item->rent_price;
                $qty = (int) $rentalDetail->quantity;

                if ($price <= 0) {
                    throw new \InvalidArgumentException('Harga item tidak boleh kurang dari atau sama dengan nol.');
                }

                if ($qty <= 0) {
                    throw new \InvalidArgumentException('Jumlah sewa tidak boleh kurang dari atau sama dengan nol.');
                }

                DB::transaction(function () use ($rentalDetail, $qty, $price, $previousSubTotal) {
                    $rentalDetail->sub_total = $qty * $price;

                    $rental = $rentalDetail->rental;
                    $rental->total_fees += $rentalDetail->sub_total - $previousSubTotal;

                    $rental->down_payment = self::calculateDownPayment($rental->total_fees);
                    $rental->save();
                });
            }
        });
    }

    protected static function calculateDownPayment(float $totalFees): float
    {
        return $totalFees > 2000000
            ? $totalFees * 0.5
            : $totalFees * 0.25;
    }
}
