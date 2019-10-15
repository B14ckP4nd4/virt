<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */
    use Faker\Generator as Faker;
    use Illuminate\Support\Str;


    $factory->define(App\virt\IP::class,function (Faker $faker){
        return [
            'ip_id' => $faker->randomNumber(),
            'server_id' => $faker->numberBetween(1,10),
            'ip' => $faker->ipv4,
            'locked' => $faker->boolean,
            'last_use' => $faker->dateTime,
        ];
    });

