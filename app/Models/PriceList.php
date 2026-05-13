<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceList extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'name', 'currency', 'is_default', 'valid_from', 'valid_until', 'notes', 'clinic_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PriceListItem::class);
    }
}
