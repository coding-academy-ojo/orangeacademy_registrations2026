<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'cohort_id', 'status', 'enrolled_at'];
    protected $casts = ['enrolled_at' => 'datetime'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function interviewEvaluation()
    {
        return $this->hasOne(InterviewEvaluation::class);
    }
}
