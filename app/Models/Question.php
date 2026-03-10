<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['questionnaire_id', 'question_text', 'question_type', 'order'];
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
