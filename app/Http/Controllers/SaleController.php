<?php

namespace admin360\Http\Controllers;

use Illuminate\Http\Request;


class SaleController extends Controller
{
    
    public function mainTable($id_bussiness){
        return view('sales.main');
    }
}
