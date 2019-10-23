<?php


    namespace App\Listeners\virt;


    class updateServerOSes
    {
        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;

        }

    }
