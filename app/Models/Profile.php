<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name_en',
        'second_name_en',
        'third_name_en',
        'last_name_en',
        'first_name_ar',
        'second_name_ar',
        'third_name_ar',
        'last_name_ar',
        'phone',
        'id_number',
        'phone_verified',
        'phone_verified_at',
        'gender',
        'date_of_birth',
        'nationality',
        'country',
        'city',
        'neighborhood',
        'address',
        'education_level',
        'university',
        'field_of_study',
        'major',
        'is_graduated',
        'graduation_year',
        'expected_graduation_year',
        'gpa_type',
        'gpa_value',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'phone_verified' => 'boolean',
        'phone_verified_at' => 'datetime',
        'is_graduated' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
