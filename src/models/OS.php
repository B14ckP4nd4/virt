<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class OS extends Model
    {
        protected $table = 'vps_oses';

        protected $guarded = ['id'];
    }
