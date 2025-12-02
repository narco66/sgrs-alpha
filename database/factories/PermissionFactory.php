<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name'       => 'perm_' . $this->faker->unique()->word(),
            'guard_name' => 'web',
        ];
    }
}
