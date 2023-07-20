<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login_id' => rand(11111111, 99999999),
            'mail_address' => $this->faker->unique()->safeEmail,
            'type' => rand(COMPANY, HR),
            'status' => CONFIRM,
            'password' => '123456789CCk', // password
        ];
    }
}
