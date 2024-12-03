<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
    ];

    public function chairs()
    {
        return $this->hasMany(Chair::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
