<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'date', 'time', 'end_time', 'location', 'status'])]

class Schedule extends Model
{
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
