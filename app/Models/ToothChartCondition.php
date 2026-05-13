<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothChartCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'colour', 'icon', 'description', 'sort_order',
    ];
}
