<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'guard_name' => array_rand(config('auth.guards')),
    ];
});

$factory->state(Permission::class, 'web', function (Faker $faker) {
    return ['guard_name' => 'web'];
});
