<?php


    namespace b14ckp4nda\virt;


    use Illuminate\Support\Facades\Facade;

    class VirtualizorFacades extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return new Virtualizor();
        }
    }
