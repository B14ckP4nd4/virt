<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class Server extends Model
    {
        protected $table = 'servers';

        protected $guarded = ['id'];

    }
