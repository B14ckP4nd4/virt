<?php


    namespace App\Listeners\virt;


    use App\virt\IP;
    use App\virt\OS;
    use App\virt\Plans;
    use App\virt\VPS;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class updateServerVPSes implements ShouldQueue
    {
        use Queueable , InteractsWithQueue;
        public $delay = 5;
        public $tries = 5;

        private $server;
        public function __construct()
        {

        }

        public function handle($event)
        {
            // Get Active Virtual Servers
            $server = $this->server = $event->server;
            $virtualServersList =  app()->make('Virtualizor')::setServer($server)->listVirtualServers();

            // try if Active Servers dosn't set
            if(!$virtualServersList && $this->attempts() < $this->tries) $this->release($this->delay);


            // remove Difference ( Soft Delete )
            $virtualServerListIDs = $virtualServersList->map(function($vps){return $vps->vpsid;})->toArray();
            $removeUnExistedVPS = VPS::where('server_id',$event->server->id)->whereNotIn('vps_id',$virtualServerListIDs)->delete();


            // Update VPSs
            foreach ($virtualServersList as $vps)
            {
                VPS::updateOrCreate([
                    'server_id' => $event->server->id,
                    'vps_id' => $vps->vpsid,
                    'ip_id' => $this->getIPId($vps),
                    'vps_name' => $vps->vps_name,
                    'hostname' => $vps->hostname,
                    'os_id' => $this->getOSid($vps),
                    'plan_id' => $this->getPlanId($vps),
                ]);
            }
        }

        private function getIPId($vps){
            return IP::where([
                [ 'server_id' , '=' ,  $this->server->id ],
                [ 'ip_id' , '=' ,  array_key_first((get_object_vars($vps->ips))) ],
            ])->first()->id;
        }

        private function getOSid($vps){
            return OS::where([
                [ 'server_id' , '=' ,  $this->server->id ],
                [ 'os_id' , '=' ,  $vps->osid ],
            ])->first()->id;
        }

        private function getPlanId($vps){
            return Plans::where([
                [ 'server_id' , '=' ,  $this->server->id ],
                [ 'plan_id' , '=' ,  $vps->plid ],
            ])->first()->id;
        }

    }
