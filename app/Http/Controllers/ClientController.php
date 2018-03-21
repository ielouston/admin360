<?php

namespace Admin360\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Admin360\Http\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function mainTable($id_bussiness){
        $repo = new ClientRepository();
        return view('clients.main');
    }
}
