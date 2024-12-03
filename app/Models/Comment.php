<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_term_id',
        'lecturer_type',
        'commentable_type',
        'commentable_id',
        'comment',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function student()
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

}
