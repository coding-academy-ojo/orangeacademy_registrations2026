<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Academy extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location', 'description'];
    public function cohorts()
    {
        return $this->hasMany(Cohort::class);
    }

    public function interviewCriteria()
    {
        return $this->hasMany(InterviewCriterion::class);
    }
}
