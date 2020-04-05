<?php


    namespace App\virt;


    use Illuminate\Database\Eloquent\Model;

    class VpsAction extends Model
    {
        protected $table = 'virt_actions';

        protected $guarded = ['id'];


        public function server()
        {
            return $this->belongsTo(Server::class);
        }

        public function vps()
        {
            return $this->belongsTo(VPS::class,'vps_id');
        }



    }
