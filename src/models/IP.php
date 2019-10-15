<?php


    namespace App;


    use Illuminate\Database\Eloquent\Model;

    class IP extends Model
    {
        protected $table = 'vps_ips';

        protected $guarded = ['id'];

    }
