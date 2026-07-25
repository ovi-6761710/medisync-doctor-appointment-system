<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $super_admin = DB::table("users")
            ->where("type", "=", "super_admin")
            ->first();

        if ($super_admin == null)
        {
            DB::table("users")
                ->insertGetId([
                    "name" => "Admin",
                    "email" => "admin@gmail.com",
                    "password" => password_hash("admin", PASSWORD_DEFAULT),
                    "email_verified_at" => now(),
                    "type" => "super_admin",
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
        }

        if (DB::table('specialities')->count() <= 0)
        {
            $specialities = [
                [
                    'name' => 'General Physician',
                    'icon' => 'fa-user-md',
                ],
                [
                    'name' => 'Dentist',
                    'icon' => 'fa-tooth',
                ],
                [
                    'name' => 'Cardiologist',
                    'icon' => 'fa-heartbeat',
                ],
                [
                    'name' => 'Dermatologist',
                    'icon' => 'fa-allergies',
                ],
                [
                    'name' => 'Pediatrician',
                    'icon' => 'fa-baby',
                ],
                [
                    'name' => 'Psychiatrist',
                    'icon' => 'fa-brain',
                ],
                [
                    'name' => 'Orthopedic Surgeon',
                    'icon' => 'fa-bone',
                ],
                [
                    'name' => 'Neurologist',
                    'icon' => 'fa-head-side-virus',
                ],
                [
                    'name' => 'Radiologist',
                    'icon' => 'fa-x-ray',
                ],
                [
                    'name' => 'Ophthalmologist',
                    'icon' => 'fa-eye',
                ],
            ];

            $records = collect($specialities)->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'created_at' => now()->utc(),
                    'updated_at' => now()->utc(),
                ];
            })->toArray();

            DB::table('specialities')->insert($records);
        }

        if (DB::table('services')->count() <= 0)
        {
            $services = [
                'Blood Test',
                'X-Ray',
                'MRI Scan',
                'Vaccination',
                'Physical Therapy',
                'Mental Health Counseling',
                'Dental Cleaning',
                'Eye Exam',
                'ECG',
                'General Consultation',
            ];

            $records = collect($services)->map(function ($item) {
                return [
                    'name' => $item,
                    'created_at' => now()->utc(),
                    'updated_at' => now()->utc(),
                ];
            })->toArray();

            DB::table('services')->insert($records);
        }
    }
}
