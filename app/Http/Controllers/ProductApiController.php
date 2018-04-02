<?php

namespace admin360\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use admin360\Product;
use admin360\Stock;
use admin360\Queue;

class ProductApiController extends Controller
{
        public function store(Request $request){

            $cUser = new UserController;
            $user = $cUser->get();
             
        	$validator = Validator::make($request->all(), [
                'clave' => 'string|required',
                'claves_aux' => 'string|required',
        		'nombre' => 'string|required',
        		'precio_compra' => 'integer|required',
        		'stock' => 'integer|required',
                'business_id' => 'integer|required',
                'situacion' => 'string|required',
                'proveedor_id' => 'integer|required'
        	]);

        	if($validator->fails()){
        		return response()->json(0, 400);
        	}

            if($this->exists($request->get('clave')) > 0){
                return response()->json(0, 409);
            }

        	$prod = new Product;
        	$prod->clave = $request->get('clave');
        	$prod->claves_aux = $request->get('claves_aux');
        	$prod->nombre = $request->get('nombre');
        	$prod->descripcion = $request->get('descripcion');
        	$prod->precio_compra = $request->get('precio_compra');
        	$prod->precio_contado = $request->get('precio_contado');
        	$prod->precio_oferta = $request->get('precio_oferta');
        	$prod->precio_mayoreo = $request->get('precio_mayoreo');
        	$prod->iva = $request->get('iva');
        	$prod->linea = $request->get('linea');
            $prod->avatar = $request->get('avatar');
            $prod->thumb = $request->get('thumb');
            
        	if($prod->save()){
                $stock = new Stock;
                $stock->nombre = $request->get('nombre');
                $stock->descripcion = $request->get('descripcion');
                $stock->comprados = $request->get('comprados');
                $stock->vendidos = $request->get('vendidos');
                $stock->stock = $request->get('stock');
                $stock->product_id = $prod->id;
                $stock->business_id = $request->get('business_id');
                $stock->situacion = $request->get('situacion');
                $stock->proveedor_id = $request->get('proveedor_id');

                if($stock->save()){
                    
                    // $this->reflectInOtherBusiness($prod, "create", $request->get('device'));

                    return response()->json($stock->id, 200);    
                }
                return response()->json(0, 500);
        	}
        	return response()->json(0, 500);
        }
        public function update(Request $request, $id){
            
            $cUser = new UserController;
            $user = $cUser->get();

        	$validator = Validator::make($request->all(), [
                'clave' => 'string|required',
                'claves_aux' => 'string|required',
        		'nombre' => 'string|required',
        		'precio_compra' => 'integer|required',
                'situacion' => 'string|required'
        	]);
            
            $stock = Stock::find($id);

        	if($validator->fails()){
        		return response()->json(0, 400);
        	}
            
            $stock->descripcion = $request->get('descripcion');
            $stock->stock = $request->get('stock');
            $stock->situacion = $request->get('situacion');
            $stock->proveedor_id = $request->get('proveedor_id');
        	$prod = Product::find($stock->product_id);

        	if($prod->clave != $request->get('clave')){

                $prod->clave = $request->get('clave');
                $prod->claves_aux = $request->get('claves_aux');    
            }
            
        	$prod->nombre = $request->get('nombre');
        	$prod->descripcion = $request->get('descripcion');
        	$prod->precio_compra = $request->get('precio_compra');
            $prod->precio_contado = $request->get('precio_contado');
            $prod->precio_oferta = $request->get('precio_oferta');
            $prod->precio_mayoreo = $request->get('precio_mayoreo');
        	$prod->iva = $request->get('iva');
        	$prod->linea = $request->get('linea');
            
            if($this->changesInProduct($stock, $request->all())){
                $this->reflectInOtherBusiness($prod, "update", $request->get('device'));
            }
        	if($prod->update() && $stock->update()){
                
        		return response()->json($stock->id, 200);
        	}
        	return response()->json(0, 500);	
        }
        public function exists($prod_key){
            $products = Product::where('claves_aux', 'LIKE', $prod_key .'%')
                                ->orWhere('claves_aux', 'LIKE','%,'. $prod_key .'%')
                                ->get(['id']);
            
            if($products->isEmpty()){
                return 0;
            }
            return $products[0]['id'];
        }
        private function changesInProduct(Stock $stock, $prod_a){
            $prod = Product::find($stock->product_id);

            if($prod->clave != $prod_a['clave']){
                return true;
            }
            else if($prod->precio_compra != $prod_a['precio_compra']){
                return true;
            }
            else if($prod->precio_contado != $prod_a['precio_contado']){
                return true;
            }
            else if($prod->precio_oferta != $prod_a['precio_oferta']){
                return true;
            }
            else if($prod->precio_mayoreo != $prod_a['precio_mayoreo']){
                return true;
            }
            else if($prod->iva != $prod_a['iva']){
                return true;
            }
            return false;
        }
        public function reflectInOtherBusiness(Product $prod, $action, $device){
            $queue = new Queue;
            $queue->action = $action;
            $queue->model = "product";
            $queue->data = $prod;
            $queue->status = "Pendiente";
            $queue->devices = $device;
            $queue->business_id = 0;

            if(!$queue->save()){
                return response()->json($queue, 500);
            }
        }
        public function find($id, $business_id){
            if($business_id > 0){
                $product = Stock::with('product')
                    ->where('id', $id)
                    ->first();    
            }
            else{
                $product = Product::with('stocks')
                            ->where('id', $id)
                            ->first();
            }

        	return response()->json($product, 200);
        }
        /*
        	return Product with stock by prod_key and business_id
         */
        public function getBy($prod_key){
            $prod = Product::where('claves_aux', 'LIKE', $prod_key .'%')
                            ->orWhere('claves_aux', 'LIKE', '%,'. $prod_key .'%')
                            ->first();
            
            if(is_null($prod)){
                return false;
            }
            return $prod;
        }
        /**
         * Get products with stocks from the business_id gave it
         * @param int $id_business
         * @return Collection<Product<Stocks>> $products
         */
        public function get($id_business){
            // ->get(['id', 'nombre', 'clave', 'precio_contado', 'precio_oferta', 'avatar', 'thumb'])
            if($id_business == 0){
                $products = Product::where('situacion', 'Activo')->orderBy('created_at', 'DESC')
                    ->paginate(20);
            }
            else{
                $products = Product::with(['stocks' => function($q) use($id_business){
                    $q->where('business_id', $id_business);
                }])
                    ->paginate(20);    
            }
            
            return response()->json($products, 200);
        }
        public function getTable(){
            $products = Product::all();

                return response()->json([
                    'columnas_cond' => Product::$columnas_cond,
                    'columnas_tabla' => Product::$columnas_tabla,
                    'data' => $products,
                    'tipos' => Product::$tipos
                    ], 200);
        }
        public function import(Request $request){
            $products = json_decode($request->get('productos'), true);
            $model = new Product;
            $response = array();

            foreach ($products as $product) {
                $client_id = $product['ID'];
                
                $validator = Validator::make($product, [
                    'clave' => 'string|required',
                    'claves_aux' => 'string|required',
                    'nombre' => 'string|required',
                    'precio_compra' => 'integer|required',
                    'stock' => 'integer|required',
                    'business_id' => 'integer|required',
                    'situacion' => 'string|required',
                    'proveedor_id' => 'integer|required'
                ]);

                if($validator->fails()){
                    $model->id = 0;
                }
    		    $model->id =  $this->exists($product['clave']);
                if($model->id > 0){
                    $stock = new Stock;
                    $stock->nombre = $product['nombre'];
                    $stock->descripcion = $product['descripcion'];
                    $stock->existencia = $product['existencia'];
                    $stock->proveedor_id = $product['proveedor_id'];
                    $stock->comprados = $product['comprados'];
                    $stock->vendidos = $product['vendidos'];
                    $stock->product_id = $model->id;
                    $stock->business_id = $product['business_id'];
                    $stock->stock = $product['stock'];
                    $stock->situacion = $product['situacion'];
                    $stock->save();
                }
                else{
                    $model = Product::create($product);    
                    $stock = new Stock;
                    $stock->nombre = $model->nombre;
                    $stock->descripcion = $model->descripcion;
                    $stock->existencia = $product['existencia'];
                    $stock->proveedor_id = $product['proveedor_id'];
                    $stock->comprados = $product['comprados'];
                    $stock->vendidos = $product['vendidos'];
                    $stock->product_id = $model->id;
                    $stock->business_id = $product['business_id'];
                    $stock->stock = $product['stock'];
                    $stock->situacion = $product['situacion'];
                    $stock->save();
                }
                $response[$client_id] = $model->id <= 0 ? $model->id : $stock->id;
            }
            return response()->json($response, 200);
        }
        public function getCategories(){
            $categories = Product::categories()->get();
            return response()->json($categories, 200);
        }
}
