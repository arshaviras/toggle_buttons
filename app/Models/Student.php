<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use TomatoPHP\FilamentTwilio\Traits\InteractsWithTwilioWhatsapp;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, SoftDeletes, InteractsWithTwilioWhatsapp;

    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'group_id',
        'email',
        'phone',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->father_name;
    }

    public function getFilamentName(): string
    {
        return "{$this->email}";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
