<?php

namespace admin360;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'nombres', 'apellidos','email', 'calle', 'numero', 'col', 'cod_postal', 'type', 'device', 'business_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token',
    ];

    public function business(){
    	return $this->belongsTo(Business::class, 'business_id');
    }
}
