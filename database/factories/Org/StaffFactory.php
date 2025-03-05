<?php

namespace Database\Factories\Org;

use App\Models\Org\JobPosition;
use App\Models\Org\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Org\Staff>
 */
class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        $paygrade = fake()->randomElement(['A', 'B', 'C', 'D', 'E', 'T']).fake()->numberBetween(1, 4);

        return [
            'user_id' => User::whereIn('user_type_id', [9, 10])->inRandomOrder()->first()->id,
            'date_of_birth' => fake()->dateTimeBetween('1966-01-01', '2007-01-01')->format('Y-m-d'),
            'staffno' => fake()->unique()->numberBetween(190000, 199999),
            'join_date' => fake()->dateTimeBetween('1985-01-01', 'now')->format('Y-m-d'),
            'paygrade' => $paygrade,
            'category' => substr($paygrade, 0, 1),
            'group' => fake()->word(),
            'shift' => fake()->word(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Staff $staff) {
            $jobPosition = JobPosition::whereNull('org_staff_id')->inRandomOrder()->first();
            if ($jobPosition) {
                $jobPosition->org_staff_id = $staff->id;
                $jobPosition->save();
            }
        });
    }
}
