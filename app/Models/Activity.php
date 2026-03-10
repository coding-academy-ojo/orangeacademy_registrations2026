<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'type',
        'action',
        'title',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public static function log(array $data): self
    {
        return self::create([
            'user_id' => $data['user_id'] ?? null,
            'admin_id' => $data['admin_id'] ?? null,
            'type' => $data['type'],
            'action' => $data['action'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'properties' => $data['properties'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
        ]);
    }

    public static function getRegistrationTypes(): array
    {
        return [
            'registration' => 'Registration',
            'profile' => 'Profile Update',
            'documents' => 'Document Upload',
            'enrollment' => 'Enrollment',
            'questionnaire' => 'Questionnaire',
            'submission' => 'Final Submission',
            'verification' => 'Email Verification',
            'login' => 'Login',
            'logout' => 'Logout',
            'admin_action' => 'Admin Action',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getRegistrationTypes()[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        $colors = [
            'registration' => 'primary',
            'profile' => 'info',
            'documents' => 'warning',
            'enrollment' => 'success',
            'questionnaire' => 'secondary',
            'submission' => 'success',
            'verification' => 'info',
            'login' => 'dark',
            'logout' => 'secondary',
            'admin_action' => 'danger',
        ];
        return $colors[$this->type] ?? 'primary';
    }
}
