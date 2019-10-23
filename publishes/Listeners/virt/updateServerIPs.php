<?php


    namespace App\Listeners\virt;


    use blackpanda\virt\Virtualizor;

    class updateServerIPs
    {

        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;
            Virtualizor::setServer($server);
        }

    }
