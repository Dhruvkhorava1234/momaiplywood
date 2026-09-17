<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'total_due',
    ];

    protected function casts(): array
    {
        return [
            'total_due' => 'decimal:2',
        ];
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
