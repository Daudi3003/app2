<?php

namespace Database\Seeders;

use App\Models\Adminstrator;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1=User::create([
            'name'=>'kelvin',
            'email'=>'kelvin@gmail.com',
            'password'=>Hash::make('12456789'),
            'usertype'=>'instructor',
         ]);

         Instructor::create([
            'user_id'=>$user1->id,
            'phone'=>'0788778906',
            'specialization'=>'Computer Science',
         ]);

          $user2=User::create([
            'name'=>'gomo',
            'email'=>'gomo@gmail.com',
            'password'=>Hash::make('98765421'),
            'usertype'=>'instructor',
         ]);

         Instructor::create([
            'user_id'=>$user2->id,
            'phone'=>'0768907768',
            'specialization'=>'Information Technology',

         ]);

         $user3=User::create([
            'name'=>'david',
            'email'=>'david@gmail.com',
            'password'=>Hash::make('gomo989@'),
            'usertype'=>'administrator',
         ]);

         Adminstrator::create([
            'user_id'=>$user3->id,
            'phone'=>'0889704886',
         ]);
         
         $user4=User::create([
            'name'=>'james',
            'email'=>'james@gmail.com',
            'password'=>Hash::make('james123'),
            'usertype'=>'student',
         ]);

         Student::create([
            'user_id'=>$user4->id,
            'phone'=>'0789012345',
            'registration_no'=>'STU12345',
         ]);

    }
}
