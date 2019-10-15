<?php


    namespace blackpanda\virt;


    use Illuminate\Support\Facades\Facade;

    class VirtualizorFacades extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return new Virtualizor();
        }
    }
