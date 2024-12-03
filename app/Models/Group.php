<?php

namespace App\Models;

use App\Enums\GroupType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Group extends Model
{
    use HasFactory, SoftDeletes, HasRelationships;

    protected $fillable = [
        'course_id',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => GroupType::class,
        'lecturer_type' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questionnaires()
    {
        return $this->hasManyDeepFromRelations($this->course(), (new Course())->questionnaires());
    }

    public function academicTerms()
    {
        return $this->hasManyDeepFromRelations($this->questionnaires(), (new Questionnaire())->academicTerms());
    }

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class)->using(GroupLecturer::class)
            ->withPivot(['lecturer_type']);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function getTotalStudentsAttribute()
    {
        return $this->hasMany(Student::class)->count();
    }
}
