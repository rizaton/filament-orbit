<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;
    protected $guarded = [];
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
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
                    RentalDetail::where('id_rental', $rental->id_rental)->update(['is_returned' => true]);
                } elseif ($rental->isDirty('status') && $rental->status === 'pending' && $rental->original('status') === 'approved') {
                    $item->increment('stock', $detail->quantity);
                    $item->update(['is_available' => true]);
                    RentalDetail::where('id_rental', $rental->id_rental)->update(['is_returned' => false]);
                }
            }
        });
    }
    public function getRentDurationInDays(?string $customReturnDate = null): int
    {
        try {
            $rentDate = Carbon::parse($this->rent_date);
            $returnDate = $customReturnDate
                ? Carbon::parse($customReturnDate)
                : Carbon::parse($this->return_date);
            return $rentDate->diffInDays($returnDate) + 1;
        } catch (\Exception $e) {
            Log::error('Error calculating rent duration', [
                'id_rental' => $this->id_rental,
                'error' => $e->getMessage(),
                'timestamp' => now(),
            ]);
            if ($this->rent_date && $this->return_date) {
                return Carbon::parse($this->rent_date)->diffInDays(Carbon::parse($this->return_date)) + 1;
            } else {
                Log::warning('Invalid rent or return date for rental', [
                    'id_rental' => $this->id_rental,
                    'rent_date' => $this->rent_date,
                    'return_date' => $this->return_date,
                    'timestamp' => now(),
                ]);
                return 0;
            }
        }
    }
    public function recalculateTotals(?string $customReturnDate = null): void
    {
        try {
            $this->loadMissing(['rentalDetails.item', 'user']);
            $rentDays = $this->getRentDurationInDays($customReturnDate);
            if (!$this->user || !$this->user->city) {
                Log::warning('User or city missing during recalculateTotals', [
                    'rental_id' => $this->id_rental,
                    'user_id' => $this->id_user,
                    'timestamp' => now(),
                ]);
                $this->down_payment = 0;
                $this->save();
                return;
            }
            $this->total_fees = $this->rentalDetails->sum(function ($detail) use ($rentDays) {
                return $detail->item?->rent_price * $detail->quantity * $rentDays ?? 0;
            });
            $this->down_payment = RentalDetail::calculateDownPayment($this->total_fees, $this->user->city);
            $this->save();
        } catch (\Throwable $th) {
            Log::error('Error recalculating rental totals', [
                'rental_id' => $this->id_rental,
                'error' => $th->getMessage(),
                'timestamp' => now(),
            ]);
        }
    }
}
