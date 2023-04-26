<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    //
    public function register(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'account created'
        ]);
    }

    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::whereEmail($request->email)->first();
        if(isset($user->id)){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('auth_token');
                return response()->json([
                    'message' => 'connected succefelly',
                    'token' => $token->plainTextToken
                    ]);
            }else{
                return response()->json([
                    'message' => 'invalid credentials',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'invalid credentials',
            ]);
        }
    }
    public function profile(){
        //recuperer  user connecter
        return new UserResource(auth()->user());
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout succefelly'
        ]);
    }
}
