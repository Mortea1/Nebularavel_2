<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'name',
        'client',
        'status',
        'progress',
        'deadline',
        'id_contract',
        'team',
        'client_id',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
