<?php


    namespace App\Listeners\virt;


    class updateServerIPs
    {

        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;
        }

    }
