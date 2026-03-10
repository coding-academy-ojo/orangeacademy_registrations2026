<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    protected $fillable = ['submission_id', 'question_id', 'answer_text', 'points_earned'];

    protected $casts = ['points_earned' => 'decimal:2'];

    public function submission()
    {
        return $this->belongsTo(AssessmentSubmission::class, 'submission_id');
    }
    public function question()
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
