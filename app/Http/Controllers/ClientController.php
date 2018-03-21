<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function mainTable($id_bussiness){
        $repo = new ClientRepository();
        return view('clients.main');
    }
}
