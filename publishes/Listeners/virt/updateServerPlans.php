<?php


    namespace App\Listeners\virt;


    class updateServerPlans
    {
        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;
        }
    }
