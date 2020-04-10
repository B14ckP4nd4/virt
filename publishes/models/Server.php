<?php


    namespace App\virt;


    use App\EncryptorTraits\Encryptable;
    use App\Events\virt\newServerHasbeenAdded;
    use Illuminate\Database\Eloquent\Model;

    class Server extends Model
    {
        use Encryptable;

        protected $encryptable = [
            'key',
            'pass',
            'licence_key',
        ];

        protected $table = 'virt_server';

        protected $guarded = ['id'];

        protected $dispatchesEvents = [
            'created' => newServerHasbeenAdded::class,
        ];

        public function vps()
        {
            return $this->hasMany(VPS::class, 'server_id');
        }

        public function ips()
        {
            return $this->hasMany(IP::class, 'server_id');
        }

        public function os()
        {
            return $this->hasMany(OS::class, 'server_id');
        }

        public function plan()
        {
            return $this->hasMany(OS::class, 'server_id');
        }

        public function havePlanId()
        {
            return ($this->main_planid > 0) ? true : false;
        }

        public function haveAdminUser()
        {
            return ($this->admin_user_id > 0) ? true : false;
        }

        public function getUnknownOS()
        {
            return OS::where([
                [ 'server_id' , '=' ,  $this->id ],
                [ 'os_id' , '=' ,  0 ],
            ])->firstOrFail()->id;
        }

        public function getUnknownPlan()
        {
            return Plans::where([
                [ 'server_id' , '=' ,  $this->id ],
                [ 'plan_id' , '=' ,  0 ],
            ])->firstOrFail()->id;
        }


    }
