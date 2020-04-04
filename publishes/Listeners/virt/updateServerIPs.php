<?php


    namespace App\Listeners\virt;


    use App\virt\IP;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class updateServerIPs implements ShouldQueue
    {

        use Queueable , InteractsWithQueue;

        public $tries = 5;

        public function __construct()
        {
            $this->delay = 5;
        }

        public function handle($event)
        {
            // Get IPS
            $server = $event->server;
            $IPlist = app()->make('Virtualizor')::setServer($server)->listIPs();

            // if IPs List in false

            if(!$IPlist && $this->attempts() < $this->tries) $this->release($this->delay);


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
