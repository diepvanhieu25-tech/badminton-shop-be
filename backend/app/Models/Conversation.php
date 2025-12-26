<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'status', // active, closed
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Khách hàng
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin phụ trách (có thể null)
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Danh sách tin nhắn
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}