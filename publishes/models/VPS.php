<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class VPS extends Model
    {
        protected $table = 'vps';

        protected $guarded = ['id'];

        public function server()
        {
            return $this->belongsTo(Server::class);
        }

        public function os()
        {
            return $this->hasOne(OS::class,'os_id');
        }

        public function ip()
        {
            return $this->hasOne(IP::class,'id','ip_id');
        }

        public function action()
        {
            return $this->hasMany(VpsAction::class,'vps_id');
        }

        public function plan()
        {
            return $this->hasOne(Plans::class , 'plan_id');
        }

    }
