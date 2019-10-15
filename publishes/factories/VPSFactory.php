<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */
    use Faker\Generator as Faker;
    use Illuminate\Support\Str;


    $factory->define(App\virt\VPS::class,function (Faker $faker){
        return [
            'vps_id' => $faker->randomNumber(),
            'server_id' => $faker->numberBetween(1,10),
            'ip_id' => $faker->numberBetween(1,10),
            'os_id' => $faker->numberBetween(1,10),
            'plan_id' => $faker->numberBetween(1,10),
            'vps_name' => $faker->name,
            'hostname' => $faker->name,
            'root_pass' => $faker->password,
        ];
    });

