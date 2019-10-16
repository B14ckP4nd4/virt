<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class Logs extends Model
    {
        protected $table = 'vps_logs';

        protected $guarded = ['id'];

        public function server()
        {
            return $this->belongsTo(Server::class,'server_id');
        }

        public function vps()
        {
            return $this->belongsTo(VPS::class,'vps_id');
        }

    }
