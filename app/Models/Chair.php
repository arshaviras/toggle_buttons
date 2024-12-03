<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Chair extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'classifier',
        'name',
        'department_id'
    ];

    protected $appends = ['lecturer_photos'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function getLecturerPhotosAttribute()
    {
        $images = [];
        foreach ($this->lecturers as $lecturer) {
            if ($lecturer->photo != null) {
                $images[] = $lecturer->photo;
            } else {
                //$images[] = 'placeholder.jpg';
                $images[] = 'https://ui-avatars.com/api/?name=' . urlencode($lecturer->last_name) . '+' . urlencode($lecturer->first_name);
            }
        }
        return $images;
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
