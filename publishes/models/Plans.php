<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class Plans extends Model
    {
        protected $table = 'vps_plans';

        protected $guarded = ['id'];

        public function server(){
            $this->belongsTo(Server::class,'server_id');
        }

    }
