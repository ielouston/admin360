<?php

namespace admin360\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use admin360\User;
use admin360\Admin;
use JWTAuth;

class UserController extends Controller
{
    public function store(Request $request){
    	
    	$validator = Validator::make($request->all(), [
    		'name' => 'string|required|max:20',
    		'password' => 'string|required|max:20',
    		'type' => 'integer|required'
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}
    
			$credentials = $request->only(['name', 'password']);
			$data = $request->only([
				'name', 'password', 'email', 'type', 'device', 'business_id'
			]);
			$user = User::firstOrCreate($credentials, $data);

    	if($user->save()){
				if ($user->type == "Admin") {
					$admin_data = $request->only([
						'name', 'password', 'situacion', 'type', 'device', 'business_id'
					]);
					$admin = Admin::firstOrCreate($admin_data);
				}
    		return response()->json($user->id, 200);
    	}
    	return response()->json(0, 500);
    }
    public function update(Request $request, $id){
    	
    	$validator = Validator::make($request->all(), [
    		'name' => 'string|required|max:20',
    		'password' => 'string|required|max:20',
    		'type' => 'integer|required'
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}

    	$user = User::find($id);
    	$user->name = $request->get('name');
    	$user->password = bcrypt($request->get('password'));
    	$user->email = $request->get('email');
    	$user->type = $request->get('type');
    	$user->device = $request->get('device');
    	$user->nombres = $request->get('nombres');
    	$user->apellidos = $request->get('apellidos');
    	$user->calle = $request->get('calle');
    	$user->numero = $request->get('numero');
    	$user->col = $request->get('col');
    	$user->cod_postal = $request->get('cod_postal');
    	$user->business_id = $request->get('business_id');

    	if($user->update()){
    		return response()->json($user->id, 200);
    	}
    	return response()->json(0, 500);
    }
    public function get(){
    	if(! $user = JWTAuth::parseToken()->authenticate() ){
            return false;
        }

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return $user;
    }

    public function import(Request $req){
    	$response = array();
    	$model = null;
    	$validator = Validator::make($req->all(), [
    		'usuarios' => 'required'
    	]);

    	if($validator->fails()){
    		return response()->json(0, 400);
    	}

    	$users = json_decode($req->get('usuarios'), true);

		foreach ($users as $user) {
			$client_id = $user['ID'];
			$model = new User;
			$model->name = $request->get('name');
			$model->password = bcrypt($request->get('password'));
			$model->email = $request->get('email');
			$model->type = $request->get('type');
			$model->device = $request->get('device');
			$model->business_id = $request->get('business_id');
			$model->save();
			
			$response[$client_id] = $model->id;
		}

		return response()->json($response, 200);
    }

    public function getAll($business_id){
    	$users = User::where('business_id', $business_id)
    				  ->where('type', '>', 2)
    				  ->get();

    	return response()->json($users, 200);
    }

    public function byToken(){
        
        if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(0, 404);
            }

        return response()->json($user , 200);
    }
}
