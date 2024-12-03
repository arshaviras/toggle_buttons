<?php

namespace App\Models;

use App\Enums\GroupType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Lecturer extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    public $translatable = [
        'first_name',
        'last_name',
        'father_name',
        'position'
    ];

    protected $fillable = [
        'chair_id',
        'first_name',
        'last_name',
        'father_name',
        'position',
        'photo',
    ];

    protected $casts = [
        'type' => GroupType::class,
        'lecturer_type' => 'array',
    ];

    public function chair()
    {
        return $this->belongsTo(Chair::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->using(GroupLecturer::class)
            ->withPivot(['lecturer_type']);
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
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->father_name;
    }

    public function getPhotoAvatarAttribute()
    {
            if ($this->photo != null) {
                $photo = $this->photo;
            } else {
                $photo = 'https://ui-avatars.com/api/?name=' . urlencode($this->last_name) . '+' . urlencode($this->first_name);
            }
        return $photo;
    }
}
