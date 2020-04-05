<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class OS extends Model
    {
        protected $table = 'virt_oses';

        protected $guarded = ['id'];

        public function server(){
            $this->belongsTo(Server::class,'server_id');
        }
    }
