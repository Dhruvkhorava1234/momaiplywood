<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_number',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'subtotal',
        'discount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    public static function generateBillNumber(): string
    {
        $date = now()->format('ymd');
        $count = static::whereDate('created_at', today())->count() + 501;

        return 'INV-'.$date.'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }

    public function getAmountInWordsAttribute(): string
    {
        return static::convertNumberToWords((int) round($this->grand_total)).' Only';
    }

    public static function convertNumberToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $words = [];

        if ($number >= 10000000) {
            $crore = intdiv($number, 10000000);
            $words[] = static::convertNumberToWords($crore).' Crore';
            $number %= 10000000;
        }

        if ($number >= 100000) {
            $lakh = intdiv($number, 100000);
            $words[] = static::convertNumberToWords($lakh).' Lakh';
            $number %= 100000;
        }

        if ($number >= 1000) {
            $thousand = intdiv($number, 1000);
            $words[] = static::convertNumberToWords($thousand).' Thousand';
            $number %= 1000;
        }

        if ($number >= 100) {
            $hundred = intdiv($number, 100);
            $words[] = static::convertNumberToWords($hundred).' Hundred';
            $number %= 100;
        }

        if ($number > 0) {
            if ($number < 20) {
                $words[] = $units[$number];
            } else {
                $part = $tens[intdiv($number, 10)];
                if ($number % 10 > 0) {
                    $part .= ' '.$units[$number % 10];
                }
                $words[] = $part;
            }
        }

        return implode(' ', $words);
    }
}
