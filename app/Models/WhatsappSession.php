<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappSession extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'phone_number',
        'session_id',
        'status',
        'qr_code',
        'auth_creds',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'auth_creds' => 'encrypted',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'whatsapp_session_id');
    }

    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }
}
