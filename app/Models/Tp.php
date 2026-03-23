<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp extends Model
{
    use HasFactory;

    protected $table = 'tps';

    protected $fillable = [
        'time',
        'comment',
        'ticket_id',
        'user_id',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
