<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_identifier',
        'firstname',
        'lastname',
        'fullname',
        'department',
        'site',
        'email',
        'password',
        'password_nohash',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Bulkorder(){
        return $this->hasMany(BulkOrder::class, 'user_id','id');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'user_id');
    }

    public function Delivery(){
        return $this->hasMany(OutstandingDelivery::class, 'user_id');
    }
}
