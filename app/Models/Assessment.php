<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'type', 'max_score', 'is_published'];

    protected $casts = ['is_published' => 'boolean', 'max_score' => 'decimal:2'];

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class)->orderBy('order');
    }
    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function getTotalPointsAttribute()
    {
        return $this->questions->sum('points');
    }
}
