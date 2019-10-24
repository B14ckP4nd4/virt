<?php


    namespace App\Listeners\virt;


    use App\virt\OS;

    class updateServerOSes
    {
        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;
            $ActiveOSList = app()->make('Virtualizor')::setServer($server)->OSTemplates();

            // remove Difference ( Soft Delete )
            $ActiveOSIDs = $ActiveOSList->map(function($os , $osID){return $osID;})->sort()->toArray();
            $removeUnExistedOS = OS::where('server_id',$event->server->id)->whereNotIn('os_id',$ActiveOSIDs)->delete();

            // Update OS List
            foreach ($ActiveOSList as $os)
            {
                OS::updateOrCreate([
                    'server_id' => $event->server->id,
                    'os_id' => $os->osid,
                    'name' => $os->name,
                    'type' => $os->type,
                    'filename' => $os->filename,
                ]);
            }
        }

    }
