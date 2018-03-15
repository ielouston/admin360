<?php

namespace Muebleria;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
	use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
    	'name', 'password', 'situacion', 'device'
    ];

    protected $hidden = [
    	'password'
    ];

    public function businesses(){
    	return $this->hasMany(Business::class, 'business_id');
    }
}
