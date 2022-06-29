<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator;

class UsersTableSeeder extends Seeder
{

    /**
     * The current Faker instance.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return Generator
     * @throws BindingResolutionException
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'admin' =>[
                'name' => $this->faker->firstName,
                'email' => 'alfalaurel.online@outlook.com',
                'paternal_name' => $this->faker->lastName,
                'maternal_name' => $this->faker->lastName,
                'password' => Hash::make('admin.321'),
                'code' => '1234',
                'birthday' => $this->faker->date('11.12.10'),
                'address' => $this->faker->streetAddress,
                'phone' => '3333333333',
                'contact_name' => $this->faker->name,
                'comments' => $this->faker->realTextBetween(50),
                'remember_token' => Str::random(10),
            ],
//            'teacher'=> [
//                'name' => $this->faker->firstName,
//                'paternal_name' => $this->faker->lastName,
//                'maternal_name' => $this->faker->lastName,
//                'email' => 'maestro@example.com',
//                'password' => Hash::make('password'),
//                'code' => '1234',
//                'birthday' => $this->faker->date('11.12.10'),
//                'address' => $this->faker->streetAddress,
//                'phone' => $this->faker->e164PhoneNumber,
//                'contact_name' => $this->faker->name,
//                'comments' => $this->faker->realTextBetween(50),
//                'remember_token' => Str::random(10),
//            ],
//            'scholar_control'=>[
//                'name' => $this->faker->firstName,
//                'paternal_name' => $this->faker->lastName,
//                'maternal_name' => $this->faker->lastName,
//                'email' => 'control_escolar@example.com',
//                'code' => '1234',
//                'password' => Hash::make('password'),
//                'birthday' => $this->faker->date('11.12.10'),
//                'address' => $this->faker->streetAddress,
//                'phone' => $this->faker->e164PhoneNumber,
//                'contact_name' => $this->faker->name,
//                'comments' => $this->faker->realTextBetween(50),
//                'remember_token' => Str::random(10),
//            ],

        ];
        foreach($users as $user ) {
            User::create($user);
        }
//        Subject::create(['name' => 'Matemáticas']);
//        Subject::create(['name' => 'Español']);
//        Subject::create(['name' => 'Ingles']);
    }
}
