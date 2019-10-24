<?php


    namespace App\Listeners\virt;


    use App\virt\IP;

    class updateServerIPs
    {

        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $event->server;
            $IPlist = app()->make('Virtualizor')::setServer($server)->listIPs();
            // remove Difference

            $activeIDs = $IPlist->map(function($ip){return $ip->ipid;})->toArray();

            $removeFromDataBase = IP::where('server_id',$event->server->id)->whereNotIn('ip_id',$activeIDs)->delete();

            // Update

            foreach ($IPlist as  $ip)
            {
                IP::updateOrCreate([
                    'ip_id' => $ip->ipid,
                    'server_id' => $event->server->id,
                    'ip' => $ip->ip,
                    'gateway' => $ip->gateway,
                    'locked' => $ip->locked,
                ]);
            }

        }

    }
