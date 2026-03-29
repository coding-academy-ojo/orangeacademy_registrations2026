<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        $firstNamesEn = ['Mohammad', 'Ahmad', 'Mahmoud', 'Abdullah', 'Omar', 'Ali', 'Yousef', 'Zaid', 'Khaled', 'Hassan', 'Hussein', 'Mustafa', 'Ibrahim', 'Fatima', 'Aisha', 'Nour', 'Sara', 'Reem', 'Laila', 'Maryam', 'Salma', 'Huda', 'Yasmeen', 'Aya'];
        $lastNamesEn = ['Obeidat', 'Khateeb', 'Masarweh', 'Khoury', 'Haddad', 'Tarawneh', 'Majali', 'Bani Mustafa', 'Otoum', 'Zawahreh', 'Adwan', 'Khreis', 'Zureiqat', 'Masaeed', 'Al-Abbadi', 'Al-Awamleh', 'Al-Fayez'];

        $firstNamesAr = ['محمد', 'أحمد', 'محمود', 'عبد الله', 'عمر', 'علي', 'يوسف', 'زيد', 'خالد', 'حسن', 'حسين', 'مصطفى', 'إبراهيم', 'فاطمة', 'عائشة', 'نور', 'سارة', 'ريم', 'ليلى', 'مريم', 'سلمى', 'هدى', 'ياسمين', 'آية'];
        $lastNamesAr = ['عبيدات', 'خطيب', 'مصاروة', 'خوري', 'حداد', 'طراونة', 'مجالي', 'بني مصطفى', 'عتوم', 'زواهرة', 'عدوان', 'خريسات', 'زريقات', 'مساعيد', 'العبادي', 'العواملة', 'الفايز'];

        $cities = ['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Ajloun', 'Jerash', 'Madaba', 'Balqa', 'Karak', 'Tafilah', 'Ma\'an'];

        $universities = [
            'University of Jordan',
            'Yarmouk University',
            'Mutah University',
            'Jordan University of Science and Technology (JUST)',
            'Hashemite University',
            'Al al-Bayt University',
            'Al-Balqa Applied University',
            'Al-Hussein Bin Talal University',
            'Tafila Technical University',
            'German Jordanian University (GJU)',
            'Princess Sumaya University for Technology',
            'Amman Arab University',
            'Applied Science Private University',
            'Zaytoonah University of Jordan'
        ];

        $educationLevels = ['High School', 'Diploma', 'Bachelor', 'Master', 'PhD'];
        $fieldsOfStudy = ['Computer Science', 'Software Engineering', 'Information Technology', 'Business Administration', 'Electrical Engineering', 'Cyber Security', 'Artificial Intelligence', 'Data Science', 'Graphic Design', 'Accounting'];

        // Randomly select indices
        $fIndex = $this->faker->numberBetween(0, count($firstNamesEn) - 1);
        $lIndex = $this->faker->numberBetween(0, count($lastNamesEn) - 1);

        // Generate a random Jordanian phone number (+962 or 07...)
        $phonePrefixes = ['079', '078', '077'];
        $phone = $this->faker->randomElement($phonePrefixes) . $this->faker->numerify('#######');

        // Pick second and third names randomly
        $sIndexEn = $this->faker->numberBetween(0, count($firstNamesEn) - 1);
        $tIndexEn = $this->faker->numberBetween(0, count($firstNamesEn) - 1);

        $sIndexAr = $this->faker->numberBetween(0, count($firstNamesAr) - 1);
        $tIndexAr = $this->faker->numberBetween(0, count($firstNamesAr) - 1);

        $neighborhoods = ['Dabouq', 'Khalda', 'Sweifieh', 'Jabal Amman', 'Abdoun', 'Shmeisani', 'Tla\' Al Ali', 'Rabieh'];
        $isGraduated = $this->faker->boolean(70); // 70% chance of being graduated

        return [
            'user_id' => User::factory(),
            'first_name_en' => $firstNamesEn[$fIndex],
            'second_name_en' => $firstNamesEn[$sIndexEn],
            'third_name_en' => $firstNamesEn[$tIndexEn],
            'last_name_en' => $lastNamesEn[$lIndex],
            'first_name_ar' => $firstNamesAr[$fIndex],
            'second_name_ar' => $firstNamesAr[$sIndexAr],
            'third_name_ar' => $firstNamesAr[$tIndexAr],
            'last_name_ar' => $lastNamesAr[$lIndex],
            'phone' => $phone,
            'phone_verified' => $isPhoneVerified = $this->faker->boolean(80),
            'phone_verified_at' => $isPhoneVerified ? now() : null,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'nationality' => 'Jordanian',
            'country' => 'Jordan',
            'city' => $this->faker->randomElement($cities),
            'neighborhood' => $this->faker->randomElement($neighborhoods),
            'address' => $this->faker->streetAddress,
            'education_level' => $this->faker->randomElement($educationLevels),
            'field_of_study' => $this->faker->randomElement($fieldsOfStudy),
            'major' => $this->faker->word,
            'university' => $this->faker->randomElement($universities),
            'is_graduated' => $isGraduated,
            'graduation_year' => $isGraduated ? $this->faker->year : null,
            'expected_graduation_year' => $isGraduated ? null : $this->faker->numberBetween(date('Y'), date('Y') + 4),
            'gpa_type' => $this->faker->randomElement(['percentage', 'gpa_4', 'grade']),
            'gpa_value' => (string) $this->faker->randomFloat(2, 2.0, 4.0),
            'has_accessibility_needs' => $hasA11y = $this->faker->boolean(15), // 15% chance
            'accessibility_details' => $hasA11y ? $this->faker->sentence() : null,
            'has_illness' => $hasIllness = $this->faker->boolean(10), // 10% chance
            'illness_details' => $hasIllness ? $this->faker->sentence() : null,
            'relative1_name' => $this->faker->name,
            'relative1_relation' => $this->faker->randomElement(['Father', 'Mother', 'Brother', 'Sister']),
            'relative1_phone' => $this->faker->numerify('079#######'),
            'relative2_name' => $this->faker->name,
            'relative2_relation' => $this->faker->randomElement(['Father', 'Mother', 'Brother', 'Sister']),
            'relative2_phone' => $this->faker->numerify('078#######'),
        ];
    }
}
