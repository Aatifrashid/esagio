<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_list_id', 'treatment_template_id', 'variant_name',
        'unit_price', 'unit_label', 'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function treatmentTemplate(): BelongsTo
    {
        return $this->belongsTo(TreatmentTemplate::class);
    }
}
