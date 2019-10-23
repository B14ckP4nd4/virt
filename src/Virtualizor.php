<?php


    namespace blackpanda\virt;


    use App\virt\Server;
    use Faker\Generator;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;

    class Virtualizor
    {

        protected $ip;
        protected $port;
        protected $key;
        protected $pass;
        protected $server;
        protected $default_password;

        public function __construct(Server $server)
        {
            $this->ip = $server->ip;
            $this->port = $server->port;
            $this->key = $server->key;
            $this->pass = $server->pass;
            $this->server = $server;
            $this->default_password = ( config('virtualizor.default_root_pass') ) ? config('virtualizor.default_root_pass') : Generator::password(10,12);
        }

        public function setServer(Server $server)
        {
            $this->ip = $server->ip;
            $this->port = (!empty($server->port)) ? config('virtualizor.default_port') : $server->port;
            $this->key = $server->key;
            $this->pass = $server->pass;
            $this->server = $server;
            return $this;
        }

        public function listVirtualServers(array $search = []) : Collection
        {
            $items = new Collection();
            $list = $this->sendRequest('vs',$search);
            $list = $this->decodeResult($list);
            if($list && isset($list->vs))
            {
                foreach ($list->vs as $vps){
                    $items->add($vps);
                }
            }
            return $items;
        }

        public function findVPS(int $vpsid)
        {
            return $this->listVirtualServers(['vpsid' => $vpsid]);
        }

        public function whereVPS(array $params)
        {
            return $this->listVirtualServers($params);
        }

        public function createVPS(array $params)
        {
            $params['virt'] = ( !isset($params['virt']) )? config('virtualizor.default_virtualization') : $params['virt'];
            $params['rootpass'] = ( !isset($params['virt']) )? $this->default_password : $params['rootpass'];
            $params['uid'] = ( !isset($params['uid']) )? $this->server->admin_user_id : $params['uid'];
            $params['plid'] = ( !isset($params['plid']) )? $this->server->main_plan_id : $params['plid'];
            $add = $this->sendRequest('addvs',$params);
            return $this->decodeResult($add);
        }

        public function deleteVPS(int $vpsid)
        {
            $delete = $this->sendRequest('vs',['delete' => $vpsid]);
            $delete = $this->decodeResult($delete);
            if($delete && isset($delete->done) && $delete->done)
            {
                return true;
            }
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

            return $response;

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


    }
