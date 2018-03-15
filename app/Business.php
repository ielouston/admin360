<?php

namespace Muebleria;
use Illuminate\Database\Eloquent\Model;
use Muebleria\Buy;
use Muebleria\Sale;
use Muebleria\User;
use Muebleria\Stock;


class Business extends Model
{
    protected $nombre;
    protected $direccion;
    protected $telefonos;
    protected $tipo;
    protected $usuario_id;

    protected $fillable = ['nombre', 'direccion', 'tipo', 'telefonos', 'usuario_id'];

    public function buys(){
        return $this->hasMany(Buy::class, 'business_id');
    }
    public function sales(){
    	return $this->hasMany(Sale::class, 'business_id');
    }
    public function users(){
    	return $this->hasMany(User::class, 'business_id');
    }
    public function stocks(){
    	return $this->hasMany(Stock::class, 'business_id');
    }
}