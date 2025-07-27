<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceJustifie extends Model
{
    protected $fillable = [
        'absence_id',
        'justification_id',
    ];

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

    public function justification()
    {
        return $this->belongsTo(Justification::class);
    }
}
