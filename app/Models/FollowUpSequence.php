<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowUpSequence extends Model
{
    use BelongsToClinic, HasFactory;

    public const TRIGGER_PLAN_SENT = 'plan_sent';

    public const TRIGGER_PLAN_VIEWED = 'plan_viewed';

    public const TRIGGER_PLAN_DECLINED = 'plan_declined';

    public const TRIGGERS = [
        self::TRIGGER_PLAN_SENT => 'Plan Sent',
        self::TRIGGER_PLAN_VIEWED => 'Plan Viewed',
        self::TRIGGER_PLAN_DECLINED => 'Plan Declined',
    ];

    protected $fillable = [
        'clinic_id',
        'name',
        'trigger_event',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(FollowUpStep::class)->orderBy('sort_order');
    }

    public function logs(): HasMany
    {
        return $this->hasManyThrough(FollowUpLog::class, FollowUpStep::class);
    }
}
