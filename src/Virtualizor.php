<?php


    namespace blackpanda\virt;


    use App\virt\Server;
    use Illuminate\Support\Str;

    class Virtualizor
    {

        protected $ip;
        protected $port;
        protected $key;
        protected $pass;
        protected $server;

        public function __construct(Server $server)
        {
            $this->ip = $server->ip;
            $this->port = $server->port;
            $this->key = $server->key;
            $this->pass = $server->pass;
            $this->server = $server;
        }

        public function listVirtualServers()
        {
            return $this->sendRequest('vs');
        }

        protected function sendRequest($action, array $params = [], array $GET = [] , array $COOKIES = [])
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
            if(!empty($params)){
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Get Response

            $response = curl_exec($ch);

            dd($response);
            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close($ch);

            return (isJson($response)) ? json_decode($response , true) : false;

        }

        private function generateAPIKey(){
            $key = Str::random(8);
            return $key . md5($this->pass . $key);
        }


    }
