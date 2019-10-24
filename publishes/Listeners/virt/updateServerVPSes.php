<?php


    namespace App\Listeners\virt;


    use App\virt\IP;
    use App\virt\OS;
    use App\virt\Plans;
    use App\virt\VPS;

    class updateServerVPSes
    {

        private $server;
        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $this->server = $event->server;

            $virtualServersList =  app()->make('Virtualizor')::setServer($server)->listVirtualServers();

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
