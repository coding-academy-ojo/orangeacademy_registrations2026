<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active',
        'verification_code',
        'verification_expires_at',
        'filtration_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function assessmentSubmissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getNameAttribute(): string
    {
        $p = $this->profile;
        return $p ? trim("{$p->first_name_en} {$p->last_name_en}") ?: $this->email : $this->email;
    }
}
