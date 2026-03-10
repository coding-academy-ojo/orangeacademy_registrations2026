<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    protected $fillable = ['assessment_id', 'question_text', 'question_type', 'options', 'correct_answer', 'points', 'order'];

    protected $casts = ['options' => 'array', 'points' => 'decimal:2'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'question_id');
    }
}
