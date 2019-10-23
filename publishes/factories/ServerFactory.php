<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */
    use Faker\Generator as Faker;
    use Illuminate\Support\Str;


    $factory->define(\App\virt\Server::class,function (Faker $faker){
        return [
            'name' => $faker->name,
            'domain' => $faker->domainName,
            'ip' => $faker->ipv4,
            'port' => $faker->numberBetween(4085,4090),
            'admin_user_id' => $faker->numberBetween(1,5),
            'main_plan_id' => $faker->numberBetween(1,5),
            'key' => $faker->password,
            'pass' => $faker->password,
            'licence_key' => $faker->password,
            'licence_expire' => $faker->numberBetween(0,30),
            'dataCenter' => $faker->name,
            'payment' => $faker->numberBetween(0,30),
            'price' => $faker->numberBetween(0,30),
        ];
    });
