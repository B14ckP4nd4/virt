<?php


    namespace App\Listeners\virt;


    use App\virt\OS;
    use App\virt\Plans;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class updateServerPlans implements ShouldQueue
    {

        use Queueable , InteractsWithQueue;
        public $tries = 5;

        private $server;

        public function __construct()
        {
            $this->delay = 5;
        }

        public function handle($event)
        {
            // Get Plans list
            $server = $this->server = $event->server;
            $ActivePlans = app()->make('Virtualizor')::setServer($server)->listPlans();


            // if IPs List in false
            if(!$ActivePlans && $this->attempts() < $this->tries) $this->release($this->delay);

            // Remove UnExisted Plans
            $ActivePlansIDs = $ActivePlans->map(function($plan){return $plan->plid;})->toArray();
            $removeUnExistedPlans = Plans::where('server_id',$event->server->id)->whereNotIn('plan_id',$ActivePlansIDs)->delete();

            // Update OS List
            foreach ($ActivePlans as $plan)
            {
                Plans::updateOrCreate([
                    'server_id' => $event->server->id,
                    'plan_id' => $plan->plid,
                    'name' => $plan->plan_name,
                    'space' => $plan->space,
                    'ram' => $plan->ram,
                    'swap' => $plan->swap,
                    'cpu' => $plan->cpu,
                    'cores' => $plan->cores,
                    'os_id' => $this->getOSid($plan->osid),
                ]);
            }

        }

        private function getOSid($osID)
        {
            return OS::where([
                [ 'server_id' , '=' , $this->server->id ],
                [ 'os_id' , '=' , $osID ],
            ])->first()->id;
        }
    }
