<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            'أحمد محمد',
            'محمد علي',
            'عبدالله حسن',
            'خالد سعيد',
            'علي أحمد',
                'فاطمة أحمد',
    'سارة محمد',
    'آلاء حسن',
    'ريم عبدالله',
    'نور خالد',
        ];

        foreach ($students as $index => $name) {
            Student::create([
                'name' => $name,
                'university_name' => 'جامعة العلوم والتكنولوجيا',
                'department_id' => 2,
                'level' => rand(1, 4), // مستوى عشوائي
                
            ]);
        }
    }
}
