<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'course',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Group::class);
    }

    public function getTotalStudentsAttribute()
    {
        return $this->students->count();
    }

    public function getFullNameAttribute()
    {
        return $this->department->name . ' ' . $this->course . ' ' . __('term');
    }
}
