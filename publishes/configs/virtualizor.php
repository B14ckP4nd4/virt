<?php

    return [
        /*
         * Default Port For Connect To Virtualizor
         */
        'default_port' => env('virt_port',4085),

        /*
         * Default Password for create new VPS
         */
        'default_root_pass' => env('virt_pass' , null),

        /*
         * Default Virtualization for create new VPS
         */
        'default_virtualization' => env('virt_virtualization', 'kvm'),

    ];
