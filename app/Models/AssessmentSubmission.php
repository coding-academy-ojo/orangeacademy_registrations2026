<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentSubmission extends Model
{
    use HasFactory;
    protected $fillable = ['assessment_id', 'user_id', 'score', 'status', 'submitted_at'];

    protected $casts = ['score' => 'decimal:2', 'submitted_at' => 'datetime'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'submission_id');
    }
}
