<?php

namespace App\Events\virt;

use App\virt\Server;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class newServerHasbeenAdded
{
    use Dispatchable, SerializesModels;


    public $server;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }


}
