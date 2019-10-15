<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class Server extends Model
    {
        protected $table = 'servers';

        protected $guarded = ['id'];

        public function vps()
        {
            return $this->hasMany(VPS::class,'server_id');
        }

        public function ip()
        {
            return $this->hasMany(IP::class,'server_id');
        }

        public function os()
        {
            return $this->hasMany(OS::class,'server_id');
        }
        public function plan()
        {
            return $this->hasMany(OS::class,'server_id');
        }

    }
