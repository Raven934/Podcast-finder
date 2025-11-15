<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        {
        $user=User::create($request->validated());
//         $user = new User();
// $user->name = $request->nom;
// $user->email = $request->email;
// $user->password = $request->password;

// $user->save();
return response()->json(["messages" => "Add user is seccussefuly" ,"user"=>$user]);
    
        }
    }


     public function login(LoginRequest $request){
        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json(['message'=>'Invalid credentials'], 401);
        
        $user=User::where('email', $request->email)->FirstOrFail();
        $token= $user->createToken('auth_token')->plainTextToken;
        return Response()->json(['message'=>'logged in','user'=>$user,'token'=>$token]);

       
     }
     public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'logged out']);
     }
}
