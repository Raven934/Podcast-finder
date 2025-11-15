<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(){
        $user=User::all();
        return Response()->json(['user'=>$user]);
    }
    public function store(RegisterRequest $request){
        $user=User::create($request->validated());
        return Response()->json(['message'=>'user created successfully', 'users'=>$user]);
    }

    public function update(UpdateUserRequest $request, User $user){
        $user->update($request->validated());

        return Response()->json(['message'=>'user updated successfully', 'users'=>$user]);

    }
    //   public function update(UpdateUserRequest $request, string $id){
    //     $id=$request->route('id');
    //     $user=User::findOrFail($id);
    //     $user->update($request->validated());

    //     return Response()->json(['message'=>'user updated successfully', 'users'=>$user]);

    // }

    public function destroy($id){
    $user=User::findOrFail($id);
    $user->delete();
    return Response()->json(['message'=> 'user deleted successfully', 'user'=>$user]);

    }
    // public function destroy(User $user){
    // $user->delete();
    // return Response()->json(['message'=> 'user deleted successfully', 'user'=>$user]);

    // }
}
