<?php

namespace admin360\Http\Repositories;

use Illuminate\Http\Request;
use admin360\Http\Controllers\Controller;
use admin360\Client;
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
