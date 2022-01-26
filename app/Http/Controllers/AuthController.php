<?php

namespace App\Http\Controllers;

use App\Models\User;

//use Illuminate\Hashing\BcryptHasher;
//use CreatePersonalAccessTokensTable;
//use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\Rules\Exists;
//use Laravel\Sanctum\PersonalAccessToken

//use Laravel\Sanctum\HasApiTokens ;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $field = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:8|confirmed'
        ]);
        $users = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => bcrypt($field['password'])
        ]);
         $token = $users->createToken('userToken')->plainTextToken;

        $response = [
            'users'=>$users,
            'token'=>$token
        ];

        return response($response);
    }

    //LOGIN

    public function login(Request $request)
    {
        $field = $request->validate([
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8',
        ]);



            //EMAIL VERIFICATION
            $users = User::where('email',$field['email'])->first();

            //PASSWORD VERIFICATION
            if(!$users || !Hash::check($field['password'], $users->password)){
                return response([
                    'message'=>'No Email or password ! please try again'
                ]);

            }


            $token = $users->createToken('userToken')->plainTextToken;

            $response = [
                'users'=>$users,
                'token'=>$token
            ];

            return response($response);
          }




    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response(['message'=>'Logged out']);
    }

    public function Users(){
        return User::all();
    }
}
