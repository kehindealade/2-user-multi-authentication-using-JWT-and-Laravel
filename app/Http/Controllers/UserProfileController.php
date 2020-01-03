<?php

namespace App\Http\Controllers;
use App\User;
use App\UserProfile;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {

      $userId = auth()->user()->id;
      
      $userProfile = User::find($userId);
      $allProfile = $userProfile->userprofile;
      
      return response()->json([
        'status' => 'success',
        'response' => [
          'userProfile' => $userProfile,
        ] 
      ]);


    }


    /**
     * update the current authenticated user's profile.
     * @param Response
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $validate = \Validator::make($request->all(), [
          'first_name' => 'min:5|max:255',
          'last_name' => 'min:5|max:255',
          'address' => 'min:5|max:255',
          'phone_number' => 'max:11',
        ]);


        if($validate->fails()){
          return response()->json(['error' => $validate->errors()]);
        }

        $userId = auth()->user()->id;
        $user = UserProfile::find($userId);
        

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');

        $user->save();
        
        return response()->json([
          'status' => 'success',
          'message' => 'Profile Updated Successfully',
          'response' => $user,
        ]);

}

    public function changePassword(Request $request){
        $userId = auth()->user()->id;

        $validate = \Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ]);

        if($validate->fails()){
            return response()->json(['error' => $validate->errors()]);
        }

        $user = User::find($userId);
        $user->password = \Hash::make($request->input('password'));

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);
    }
}
