<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sale;
use Validator;

class SaleRepository extends Controller
{

	public function getUnfinished($id_bussiness){
		$sales = Sale::Unfinished()->get();
	}
    public function getByType($id_bussiness, $type){

        $sales = Sale::Type($type)->get();
        
        return $sales;
    }
    public function getCanceled($id_bussiness){
        $sales = Sale::where('sales.situacion', 'Cancelada')->get();
        
        return $sales;
    }

}
