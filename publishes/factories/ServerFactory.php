<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */
    use Faker\Generator as Faker;
    use Illuminate\Support\Str;


    $factory->define(\App\virt\Server::class,function (Faker $faker){
        return [
            'name' => $faker->name,
            'domain' => $faker->domainName,
            'ip' => $faker->ipv4,
            'key' => $faker->password,
            'pass' => $faker->password,
            'dataCenter' => $faker->name,
            'payment' => $faker->numberBetween(0,30),
            'price' => $faker->numberBetween(0,30),
        ];
    });
