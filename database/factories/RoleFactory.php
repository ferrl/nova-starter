<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle,
        'guard_name' => array_rand(config('auth.guards')),
    ];
});

$factory->state(Role::class, 'web', function (Faker $faker) {
    return ['guard_name' => 'web'];
});
