<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class ServerAction extends Model
    {
        protected $table = 'vps_actions';

        protected $guarded = ['id'];

    }
