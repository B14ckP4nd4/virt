<?php

namespace App\Events\virt;

use App\virt\VPS;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VpsHasBeenDeleted
{
    use Dispatchable, SerializesModels;


    public $vps;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(VPS $vps)
    {
        $this->vps = $vps;
    }

}
