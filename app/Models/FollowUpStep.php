<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowUpStep extends Model
{
    use HasFactory;

    public const CHANNEL_EMAIL = 'email';

    public const CHANNEL_SMS = 'sms';

    public const CHANNELS = [
        self::CHANNEL_EMAIL => 'Email',
        self::CHANNEL_SMS => 'SMS',
    ];

    protected $fillable = [
        'follow_up_sequence_id',
        'delay_hours',
        'channel',
        'subject',
        'body_template',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'delay_hours' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(FollowUpSequence::class, 'follow_up_sequence_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(FollowUpLog::class);
    }
}
