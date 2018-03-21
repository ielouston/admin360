<?php

namespace Admin360;
use Illuminate\Database\Eloquent\Model;
use Admin360\Buy;
use Admin360\Sale;
use Admin360\User;
use Admin360\Stock;


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
