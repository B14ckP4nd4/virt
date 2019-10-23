<?php


    namespace blackpanda\virt;


    use App\Events\virt\newServerHasbeenAdded;
    use App\Events\virt\VpsHasBeenDeleted;
    use App\Listeners\virt\updateServerIPs;
    use App\Listeners\virt\updateServerOSes;
    use App\Listeners\virt\updateServerPlans;
    use App\Listeners\virt\updateServerVPSes;
    use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

    class VirtualizorEventServiceProvider extends ServiceProvider
    {
        protected $listen = [
            newServerHasbeenAdded::class => [
                updateServerIPs::class,
                updateServerOSes::class,
                updateServerPlans::class,
                updateServerVPSes::class,
            ],

            VpsHasBeenDeleted::class => [

            ],
        ];
        public function boot()
        {
            parent::boot();
        }


    }
