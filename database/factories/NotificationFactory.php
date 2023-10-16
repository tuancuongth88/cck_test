<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\DistributionNotification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::random(20),
            Notification::TYPE => DistributionNotification::class,
            Notification::NOTIFIABLE_ID => Auth::id(),
            Notification::NOTIFIABLE_TYPE => User::class,
            Notification::DATA => $this->faker->text(200)
        ];
    }
}
