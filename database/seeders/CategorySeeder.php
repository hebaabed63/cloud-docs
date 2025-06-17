<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('categories')->insert([
    [
        'name' => 'تعليمي',
        'keywords' => json_encode(['مدرسة', 'تعلم', 'منهاج', 'school', 'learning', 'curriculum']),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'صحي',
        'keywords' => json_encode(['دواء', 'صحة', 'مريض', 'medicine', 'health', 'patient']),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'تقني',
        'keywords' => json_encode(['تكنولوجيا', 'برمجة', 'حاسوب', 'technology', 'programming', 'computer']),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'مالي',
        'keywords' => json_encode(['بنك', 'مال', 'دفع', 'bank', 'money', 'payment']),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'غير مصنف',
        'keywords' => json_encode([]), // فارغ
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);



    }
}
