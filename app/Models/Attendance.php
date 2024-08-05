<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'attendance_date',
        'user_id',
        'attendance_by'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendedBy() 
    {
        return $this->belongsTo(User::class, 'attendance_by');
    }

}
