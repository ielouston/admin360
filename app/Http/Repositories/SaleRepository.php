<?php

namespace admin360\Http\Repositories;

use Illuminate\Http\Request;
use admin360\Http\Controllers\Controller;
use admin360\Sale;
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
