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
            $ActivePlans = app()->make('Virtualizor')::setServer($server)->listPlans();

            dd($ActivePlans);
            // Remove UnExisted Plans
            $ActivePlansIDs = $ActivePlans->map(function($vps){return $vps->osid;})->toArray();
//            dd();
        }
    }
