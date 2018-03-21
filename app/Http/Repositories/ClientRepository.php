<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use Validator;


class ClientRepository extends Controller
{
    public function getActives($id_bussiness){
        
        $clients = Client::Active()->get();
        
        return $clients;
    }
    public function getInactives($id_bussiness){
        $clients = Client::Inactive()->get();
        return $clients;
    }
    public function getWithCredit($id_bussiness){
        
        $clients = Client::ActiveCredit()->Active()->get(['clients.*']);
        return $clients;
    }

}
