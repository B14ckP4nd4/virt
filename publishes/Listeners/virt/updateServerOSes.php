<?php


    namespace App\Listeners\virt;


    use App\virt\OS;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class updateServerOSes implements ShouldQueue
    {
        use Queueable , InteractsWithQueue;
        public $tries = 5;

        public function __construct()
        {
            $this->delay = 5;
        }

        public function handle($event)
        {
            // Get Active OSes
            $server = $event->server;
            $ActiveOSList = app()->make('Virtualizor')::setServer($server)->OSTemplates();

            // Retry if OSes list Doen't Set

            if(!$ActiveOSList && $this->attempts() < $this->tries) $this->release($this->delay);

            // remove Difference ( Soft Delete )
            $ActiveOSIDs = $ActiveOSList->map(function($os , $osID){return $osID;})->sort()->toArray();
            $removeUnExistedOS = OS::where('server_id',$event->server->id)->whereNotIn('os_id',$ActiveOSIDs)->delete();

            // Update OS List
            foreach ($ActiveOSList as $id => $os)
            {
                OS::updateOrCreate([
                    'server_id' => $event->server->id,
                    'os_id' => $id,
                    'name' => $os->name,
                    'type' => $os->type,
                    'filename' => $os->filename,
                ]);
            }
        }

    }
