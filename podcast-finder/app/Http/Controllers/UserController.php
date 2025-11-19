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
        try {
            $this->authorize('viewAny', User::class);
            
            $users = User::all();
            
            return response()->json([
                'message' => 'Users retrieved successfully',
                'users' => $users,
                'count' => $users->count()
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'Failed to retrieve users from database.',
                'details' => 'Please check database connection and try again.'
            ], 500);
    };
}

    public function show(User $user){
        try {
            $this->authorize('view', $user);
            
            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Access Denied',
                'message' => 'You are not authorized to view this user.',
            ], 403);
            
        };
    }
    
    public function store(RegisterRequest $request){
        try {
            $this->authorize('create', User::class);
            
            $user = User::create($request->validated());
            
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
            
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'error' => 'Duplicate Entry',
                    'message' => 'Email address already exists.',
                    'details' => 'Please use a different email address.'
                ], 422);
            }
    };
}

    public function update(UpdateUserRequest $request, User $user){
        try {
            $this->authorize('update', $user);
            
            if (!$user) {
                return response()->json([
                    'error' => 'User Not Found',
                    'message' => 'The user you are trying to update does not exist.',
                    'details' => 'Please check the user ID and try again.'
                ], 404);
            }
            
            $user->update($request->validated());
            
            $user->refresh();
            
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
            
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'error' => 'Duplicate Entry',
                    'message' => 'Email address already exists.',
                    'details' => 'Please use a different email address.'
                ], 422);
            }
        };
    }
    //   public function update(UpdateUserRequest $request, string $id){
    //     $id=$request->route('id');
    //     $user=User::findOrFail($id);
    //     $user->update($request->validated());

    //     return Response()->json(['message'=>'user updated successfully', 'users'=>$user]);

    // }

    public function destroy($id){
        try {
            $user = User::findOrFail($id);
            
            $this->authorize('delete', $user);
            
            $userData = $user->toArray();
            
            $user->delete();
            
            return response()->json([
                'message' => 'User deleted successfully',
                'deleted_user' => $userData
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User Not Found',
                'message' => 'The user you are trying to delete does not exist.',
                'details' => 'Please check the user ID and try again.'
            ], 404);
        }
    }
    // public function destroy(User $user){
    // $user->delete();
    // return Response()->json(['message'=> 'user deleted successfully', 'user'=>$user]);

    // }
}
