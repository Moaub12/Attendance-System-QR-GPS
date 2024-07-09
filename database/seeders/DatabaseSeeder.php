<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([[
            'name' => 'mohamad Ayoubi',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ],[
            'name' => 'Ali Ayoubi',
            'email' => 'test1@gmail.com',
            'password' => Hash::make('password'),
        ]]);
        DB::table('years')->insert([
            ['name' => 'First'],
            ['name' => 'Second'],
            ['name' => 'Third'],
            ['name' => 'Fourth'],
            ['name' => 'Fith'],
            // Add more years as needed
        ]);
        DB::table('departements')->insert([
            ['name' => 'Commun Tonc'],
            ['name' => 'Electrical'],
            ['name' => 'Mechanical'],
            ['name' => 'Civil'],
            ['name' => 'Petro'],
            // Add more years as needed
        ]);
        DB::table('semesters')->insert([
            ['name' => 'Semester 1'],
            ['name' => 'Semester 2'],
            ['name' => 'Semester 3'],
            ['name' => 'Semester 4'],
            ['name' => 'Semester 5'],
            ['name' => 'Semester 6'],
            ['name' => 'Semester 7'],
            ['name' => 'Semester 8'],
            ['name' => 'Semester 9'],
            // Add more years as needed
        ]);
        DB::table('courses')->insert([
            ['name' => 'Static',
            'year_id'=>'1',
            'departement_id'=>'1',
            'code'=>'MEC001',
            'semester_id'=>'1',
        ],
        [
            'name' => 'Calculus I',
            'year_id' => 1,
            'department_id' => 1,
            'code' => 'MATH101',
            'semester_id' => 1,
        ],
        [
            'name' => 'Algebra',
            'year_id' => 1,
            'department_id' => 1,
            'code' => 'MATH102',
            'semester_id' => 1,
        ],
            
        ]);  
        DB::table('coordinates')->insert([
            ['name' => 'Faculty of Engineering',
            'longitude' =>'35.830935642066',
            'latitude' =>'34.416466568299'],
            ['name' => 'Home',
            'longitude' =>'35.79423151642871',
            'latitude' =>'34.37180323570666']
        ]);

    }
}
