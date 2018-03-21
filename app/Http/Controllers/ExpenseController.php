<?php

namespace App\Http\Controllers;
use App\Expense;
use Illuminate\Http\Request;
use Validator;

class ExpenseController extends Controller
{
    public function store(Request $req){
    	
    	$validator = Validator::make($req->all(), [
    		'nombre' => 'string|required'
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}
    	
    	if(!$this->exist($req->get('nombre'))){
    		$exp = new Expense;
    		$exp->nombre = $req->get('nombre');

    		if($exp->save()){
    			return response()->json($exp->id, 200);
    		}
    	}
    	
    	return response()->json(0, 500);
    }
    public function exist($nombre){
    	$exp = Expense::where('nombre', $nombre)->get(['id']);

    	if($exp->isEmpty()){
    		return false;
    	}
    	return true;
    }
    public function getAll(){
    	$exps = Expense::all();

    	return response()->json($exps, 200);
    }
}
