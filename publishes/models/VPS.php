<?php


    namespace App\virt;


    use App\EncryptorTraits\Encryptable;
    use App\Events\virt\VpsHasBeenDeleted;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class VPS extends Model
    {
        use Encryptable;

        protected $encryptable = [
            'root_pass',
        ];

        use SoftDeletes;

        protected $table = 'virt_vps';

        protected $guarded = ['id'];

        protected $dispatchesEvents = [
            'deleted' => VpsHasBeenDeleted::class,
        ];

        public static function boot()
        {
            parent::boot();
        }

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
