<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupLecturer extends Pivot
{
    //public $incrementing = true;

    protected $casts = [
        'lecturer_type' => 'array',
    ];

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class)->using(GroupLecturer::class)
            ->withPivot(['lecturer_type']);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->using(GroupLecturer::class)
            ->withPivot(['lecturer_type']);
    }
}
