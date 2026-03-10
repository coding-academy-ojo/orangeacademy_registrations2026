<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cohort extends Model
{
    use HasFactory;
    protected $fillable = ['academy_id', 'name', 'description', 'start_date', 'end_date', 'status'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];
    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
