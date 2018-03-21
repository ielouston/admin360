<?php

namespace Admin360\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Admin360\Movement;
use Admin360\Expense;
use Illuminate\Support\Facades\DB;

class MovementApiController extends Controller
{
	public function __construct(){
		$this->middleware('jwt.auth');
	}
	
    public function updateDates(Request $req){
        $movs_list = $req->get('movs_list');
        $fecha = $req->get('fecha');
        $cont = 0;

        foreach ($movs_list as $mov) {

            $mov = Movement::find($mov['id']);
            // $fecha_hora = explode(' ', $mov->fecha);
            // $mov->fecha = $fecha ." ". $fecha_hora[1];
            $ms = $cont > 9 ? $cont : '0'. $cont;
            $mov->fecha = $fecha . ':'. $ms;
            if(!$mov->update()){
                return $cont;
            }
            $cont++;
        }
        return $cont;
    }
    public function parseDate($source, $format){

        $parsedDate = Carbon::createFromFormat($format, $source)->toDateTimeString();

        return substr($parsedDate, 0, 10);
    }
    public function store(Request $req){

    	$validator = Validator::make($req->all(), [
    		'movimiento' => 'string|required',
    		'fecha' => 'string|required',
    		'situacion' => 'string|required',
    		'cliente' => 'string|required',
    		'business_id' => 'string|required'
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}

    	$mov = new Movement;
    	$mov->sale_id = $req->get('sale_id');
    	$mov->product_id = $req->get('product_id');
    	$mov->buy_id = $req->get('buy_id');
    	$mov->payment_id = $req->get('payment_id');
    	$mov->expense_id = $req->get('expense_id');
    	$mov->nota = $req->get('nota');
    	$mov->movimiento = $req->get('movimiento');
    	$mov->entradas = $req->get('entradas');
    	$mov->salidas = $req->get('salidas');
    	$mov->fecha = $req->get('fecha');
    	$mov->situacion = $req->get('situacion');
    	$mov->cliente = $req->get('cliente');
    	$mov->comentarios = $req->get('comentarios');
    	$mov->business_id = $req->get('business_id');

    	if($mov->save()){
    		return response()->json($mov->id, 200);
    	}
    	return response()->json(0, 500);
    }

    public function update($id, Request $req){

    	$validator = Validator::make($req->all(), [
    		'fecha' => 'string|required',
    		'situacion' => 'string|required',
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}

    	$mov = Movement::find($id);
    	$mov->fecha = $req->get('fecha');
    	$mov->situacion = $req->get('situacion');
    	$mov->comentarios = $req->get('comentarios');

    	if($mov->update()){
    		return response()->json($mov->id, 200);
    	}
    	return response()->json(0, 500);
    }
    public function import(Request $req){
        $model = new Expense;
        $response = array();
        $resp_exps = array();
        $resp_movs = array();
        $movs_gastos = null;

        $validator = Validator::make($req->all(), [
            'gastos' => 'required',
            'movimientos' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(0, 400);
        }
        
        $gastos = json_decode($req->get('gastos'), true);
        $movs = json_decode($req->get('movimientos'), true);

        foreach ($gastos as $gasto) {
            $client_id = $gasto['ID'];

            if(!empty($gastos['movements'])){
            	$movs_gastos = $gastos['movements'];
            }
        	    
            $model = Expense::create($gasto);
            $resp_exps[$client_id] = $model->id;

            if($model->id > 0){

            	if(!is_null($movs_gastos)){
            		foreach ($movs_gastos as $mov_gasto) {
            		    $client_id = $mov_gasto['ID'];
            		    $mov_gasto['expense_id'] = $model->id;
            		    $mov_model = Movement::create($mov_gasto);
            		    $resp_movs[$client_id] = $mov_model->id;    
            		}	
            	}
            }
        }

        foreach ($movs as $mov) {
            $client_id = $mov['ID'];
            $mov_model = Movement::create($mov);  
            $resp_movs[$client_id] = $mov_model->id;
        }

        $response['movimientos'] = $resp_movs;
        $response['gastos'] = $resp_exps;

        return response()->json($response, 200);
    }

    public function getAll(Request $req){

        if(count($req->all()) == 0){
            $movs = Movement::all();    
        }
        else{
            $date_from = $req->get('date_from');
            $date_to = $req->get('date_to');
            $both = $req->get('both');
            $business_id = $req->get('business_id');
            
            $movs = Movement::ByDates($date_from, $date_to, $both, $business_id)->get();
        }
    	return response()->json($movs, 200);
    }

    public function getByBusiness($business_id){
        $movs = Movement::where('business_id', $business_id)->get();

        return response()->json($movs, 200);
    }
    public function cancelBy($field, $value){
        if(Movement::where($field, $value)->update(['situacion' => 'Cancelado'])){
            return response()->json($field, 500);
        }
    }
}
