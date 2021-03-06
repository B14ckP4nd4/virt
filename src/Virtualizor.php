<?php


    namespace blackpanda\virt;


    use App\virt\IP;
    use App\virt\OS;
    use App\virt\Plans;
    use App\virt\Server;
    use App\virt\VPS;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;

    class Virtualizor
    {

        protected $ip;
        protected $port;
        protected $key;
        protected $pass;
        protected $server;
        protected $default_password;
        protected $spaceLimit;

        public function __construct(Server $server)
        {
            $this->ip = $server->ip;
            $this->port = $server->port;
            $this->key = $server->key;
            $this->pass = $server->pass;
            $this->server = $server;
            $this->default_password = ( config('virtualizor.default_root_pass') ) ? config('virtualizor.default_root_pass') : Hash::make(Str::random(8));
            $this->spaceLimit = ( config('virtualizor.space_limit') ) ? config('virtualizor.space_limit') : 15 ;
        }

        public function _setDefaultPassword(string $defaultPassword) : void {
            $this->default_password = $defaultPassword;
        }

        public function _setSpaceLimit(int $spaceLimit) : void {
            $this->spaceLimit = $spaceLimit;
        }

        public function setServer(Server $server)
        {
            $this->ip = $server->ip;
            $this->port = $server->port;
            $this->key = $server->key;
            $this->pass = $server->pass;
            $this->server = $server;
            $this->default_password = ( config('virtualizor.default_root_pass') ) ? config('virtualizor.default_root_pass') : Hash::make(Str::random(8));
            return $this;
        }

        public function listVirtualServers(array $search = []) : Collection
        {
            $items = new Collection();
            $list = $this->sendRequest('vs',$search);
            return ($list && isset($list->vs)) ? new Collection($list->vs) : new Collection();
        }

        public function findVPS(int $vpsid)
        {
            return $this->listVirtualServers(['vpsid' => $vpsid]);
        }

        public function whereVPS(array $params)
        {
            return $this->listVirtualServers($params);
        }

        public function createVPS(array $params , $save = true , $chooseBestStorage = true)
        {
            // Set Params For Creation
            $params['virt'] = ( !isset($params['virt']) )? config('virtualizor.default_virtualization') : $params['virt'];
            $params['rootpass'] = ( !isset($params['virt']) )? $this->default_password : $params['rootpass'];
            $params['uid'] = ( !isset($params['uid']) )? $this->server->admin_user_id : $params['uid'];
            $params['plid'] = ( !isset($params['plid']) )? $this->server->main_plan_id : $params['plid'];
            $params['addvps'] = ( !isset($params['addvps']) )? 1 : $params['addvps'];
            if( $chooseBestStorage )
            {
                $bestStorage = $this->bestStorageForCreateVPS();
                $params['stid'] = ( $bestStorage ) ? $bestStorage->first()->stid : false;
                if(!$params['stid']) return false;

            }


            // Start Create VPS
            $newVPS = $this->sendRequest('addvs',$params);
            if($newVPS && isset($newVPS->done) && $save)
            {
                VPS::create([
                    'server_id' => $this->server->id,
                    'vps_id' => $newVPS->newvs->vpsid,
                    'ip_id' => $this->getIPId($newVPS->newvs),
                    'vps_name' => $newVPS->newvs->vps_name,
                    'hostname' => $newVPS->newvs->hostname,
                    'os_id' => $this->getOSid($newVPS->newvs),
                    'plan_id' => $this->getPlanId($newVPS->newvs),
                    'root_pass' => $newVPS->newvs->pass,
                ]);
            }
            return $newVPS;
        }

        public function deleteVPS(int $vpsid)
        {
            $delete = $this->sendRequest('vs',['delete' => $vpsid]);
            return ( $delete && isset($delete->done) && $delete->done );
        }

        public function listIPs()
        {
            $list = $this->sendRequest('ips',['reslen'=> 999]);
            $items = (isset($list->ips)) ? new Collection($list->ips) : new Collection();
            return $items;
        }

        public function OSTemplates()
        {
            $list = $this->sendRequest('ostemplates');
            return (isset($list->ostemplates)) ? new Collection($list->ostemplates) : new Collection();
        }

        public function listPlans()
        {
            $list = $this->sendRequest('	plans',['reslen'=> 999]);
            $items = (isset($list->plans)) ? new Collection($list->plans) : new Collection();
            return $items;
        }

        public function startVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'action' => 'start',
                'vpsid' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done ) ? true : false ;
        }

        public function stopVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'action' => 'stop',
                'vpsid' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done ) ? true : false ;
        }

        public function restartVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'action' => 'restart',
                'vpsid' => $vpsid,
            ]);

            // timeout problem , cuz restart process need many time
            // return ( isset($request->done) && $request->done ) ? true : false ;

            return true;
        }

        public function powerOffVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'action' => 'poweroff',
                'vpsid' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done ) ? true : false ;
        }

        public function suspendVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'suspend' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done == 1 ) ? true : false ;
        }

        public function unSuspendVPS(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'unsuspend' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done == 1 ) ? true : false ;
        }

        public function networkSuspend(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'suspend_net' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done == 1 ) ? true : false ;
        }

        public function networkUnSuspend(int $vpsid)
        {
            $request = $this->sendRequest('vs',[],[
                'unsuspend_net' => $vpsid,
            ]);

            return ( isset($request->done) && $request->done == 1 ) ? true : false ;
        }


        public function listStorages()
        {
            $list = $this->sendRequest('storage',['reslen' => 999]);
            return (isset($list->storage)) ? $list->storage : false;
        }

        public function bestStorageForCreateVPS()
        {
            $list = $this->listStorages();

            if(!$list && !isset($list->storage)) return false;

            $storages = new Collection();

            foreach ($list as $items)
            {
                $storages->add($items);
            }

            $maxFree = $storages->max(function ($item){return $item->free;});

            if($maxFree >= $this->spaceLimit ) return $storages->where('free','=',$maxFree);

            return false;
        }


        protected function sendRequest($action, array $params = [], array $GET = [], array $COOKIES = [])
        {
            $ch = curl_init();
            $GET = array_merge(
                [
                    'act' => $action,
                    'api' => 'json',
                    'apikey' => rawurlencode($this->generateAPIKey()),
                ]
                , $GET
            );
            curl_setopt($ch, CURLOPT_URL, 'https://' . $this->ip . ':' . $this->port . '/index.php?' . http_build_query($GET));
            // Time OUT
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            // Turn off the server and peer verification (TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 9000);
            // UserAgent
            curl_setopt($ch, CURLOPT_USERAGENT, 'BlackPanda Virtualizor');
            // Cookies
            if (!empty($cookies)) {
                curl_setopt($ch, CURLOPT_COOKIESESSION, true);
                curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, '', '; '));
            }
            // Params
            if (!empty($params)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Get Response

            $response = curl_exec($ch);

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);


            return $this->decodeResult($response);

        }

        private function generateAPIKey()
        {
            $key = Str::random(8);
            return $key . md5($this->pass . $key);
        }

        private function decodeResult(string $json){
            $json = json_decode($json);
            if(json_last_error() == JSON_ERROR_NONE)
            {
                return $json;
            }
            return false;
        }

        private function getIPId($vps){


            return IP::where([
                [ 'server_id' , '=' ,  $this->server->id ],
                [ 'ip' , '=' ,  $vps->ips[0] ],
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
