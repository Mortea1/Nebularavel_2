<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'title',
        'project_id',
        'priority',
        'status',
        'assigned_to',
        'created_at_long',
        'creator',
        'type',
        'category',
        'description',
        'steps',
        'in_contract',
        'comments',
        'attachments',
        'expected',
        'time_spent',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tps()
    {
        return $this->hasMany(Tp::class);
    }

    public function validations()
    {
        return $this->hasMany(Validation::class);
    }
}
