<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'hours_included',
        'hours_used',
        'hourly_rate',
        'start_date',
        'end_date',
        'project_id',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // ─── Accessors ───────────────────────────────────────────────
    public function getRemainingHoursAttribute(): int
    {
        return max(0, $this->hours_included - $this->hours_used);
    }
}
