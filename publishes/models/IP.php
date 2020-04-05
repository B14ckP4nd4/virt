<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class IP extends Model
    {
        protected $table = 'virt_ips';

        protected $guarded = ['id'];

        public function server()
        {
            return $this->belongsTo(Server::class);
        }

        public function vps()
        {
            return $this->hasOne(VPS::class,'ip_id');
        }

    }
