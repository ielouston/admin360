<?php

namespace admin360;
 
use Illuminate\Database\Eloquent\Model;
use admin360\Scopes\SearchPaginateAndOrder;

class Product extends Model
{
    protected $clave;
    protected $claves_aux;
    protected $nombre;
    protected $descripcion;
    protected $precio_compra;
    protected $precio_contado;
    protected $precio_oferta;
    protected $precio_mayoreo;
    protected $iva;
    protected $linea;
    protected $avatar;
    protected $thumb;

    protected $fillable = [
    	'clave', 'claves_aux','nombre', 'descripcion', 'precio_compra', 'precio_contado', 'precio_oferta', 'precio_mayoreo', 'iva', 'linea', 'avatar', 'thumb'
    ];

    public static $table_name = "products";
    public static $columnas_tabla = ['clave', 'nombre','descripcion', 'precio_compra','precio_contado', 'precio_oferta', 'precio_mayoreo', 'linea', 'updated_at'];

    public static $columnas_cond = ['clave', 'nombre', 'linea', 'precio_compra', 'precio_contado', 'precio_mayoreo', 'precio_oferta', 'updated_at'];

    public static $tipos = ['Activos', 'Inhabilitados'];
    
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SearchPaginateAndOrder);
    }
    public function scopeActive($query){
        return $query->where('products.situacion', 'Activo');
    }
    public function scopeInactive($query){
        return $query->where('products.situacion', 'Inactivo');
    }
    public function stocks(){
        return $this->hasMany(Stock::class, 'product_id');
    }
    public function stock($business_id){
        return $this->hasOne(Stock::class, 'product_id')->where('business_id', $business_id);
    }

    public function categories(){
        return $this->selectRaw('distinct(linea) as categorias');
    }
    public function getAvatarAttribute($value){
        return '../../storage/avatars/full/' . $value;
    }
    public function getThumbAttribute($value){
        return '../../storage/avatars/thumb/'. $value;
    }
}
