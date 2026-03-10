<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewEvaluation extends Model
{
    protected $fillable = ['enrollment_id', 'admin_id', 'scores', 'total_score', 'notes'];

    protected $casts = [
        'scores' => 'array',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
