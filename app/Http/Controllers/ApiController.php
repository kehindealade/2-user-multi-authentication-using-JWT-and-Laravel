<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use App\User;

class ApiController extends Controller
{  
    public function userLogin(Request $request){
		$credentials = $request->only('email', 'password');
		$token = null;
		try {
		    if (!$token = JWTAuth::attempt($credentials)) {
		        return response()->json([
		            'response' => 'error',
		            'message' => 'invalid_email_or_password',
		        ]);
		    }
		} catch (JWTAuthException $e) {
		    return response()->json([
		        'response' => 'error',
		        'message' => 'failed_to_create_token',
		    ]);
		}
		return response()->json([
		    'response' => 'success',
		    'result' => [
		        'token' => $token,
		        //'message' => 'I am front user',
		    ],
		]);
    }


	public function registerUser(Request $request){
		$validate = \Validator::make($request->all(), [
			'name' => 'required|max:255|unique:users',
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'address' => 'required|max:255',
			'phone_number' => 'required|max:255',
			'email' => 'required|email|unique:users|max:255',
			'password' => 'required|min:6|max:255',
			'confirm_password' => 'required|min:6|max:255|same:password',

		]);

		if($validate->fails()){
			return response()->json(['error' => $validate->errors()]);
		}

		$user = new User;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = \Hash::make($request->password);

		$user->save();

		$userprofile = new \App\UserProfile;
		$userprofile->first_name = $request->first_name;
		$userprofile->last_name = $request->last_name;
		$userprofile->address = $request->address;
		$userprofile->phone_number = $request->phone_number;
		$userprofile->user_id = $user->id;

		$userprofile->save();

		return response()->json([
			'status' => 'success',
			'message' => 'User registered successfully',
		]);
	}

    public function adminLogin(Request $request){
		$credentials = $request->only('email', 'password');
		$token = null;
		try {
		    if (!$token = JWTAuth::attempt($credentials)) {
		        return response()->json([
		            'response' => 'error',
		            'message' => 'invalid_email_or_password',
		        ]);
		    }
		} catch (JWTAuthException $e) {
		    return response()->json([
		        'response' => 'error',
		        'message' => 'failed_to_create_token',
		    ]);
		}
		return response()->json([
		    'response' => 'success',
		    'result' => [
		        'token' => $token,
		        'message' => 'I am leaser user',
		    ],
		]);
	}
	

	
}
