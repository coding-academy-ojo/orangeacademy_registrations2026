<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewCriterion extends Model
{
    protected $fillable = ['academy_id', 'name', 'weight'];

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }
}
