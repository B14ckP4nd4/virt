<?php


    namespace App\Listeners\virt;


    use App\virt\OS;
    use App\virt\Plans;

    class updateServerPlans
    {
        private $server;
        public function __construct()
        {

        }

        public function handle($event)
        {
            $server = $this->server = $event->server;
            $ActivePlans = app()->make('Virtualizor')::setServer($server)->listPlans();

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
