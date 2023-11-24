<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){
        $registrasiData = $request->all();

        $validate = Validator::make($registrasiData, [
            'username' => 'required|max:60',
            'email' => 'required',
            'password' => 'required|min:8',
            'notelp' => 'required',
            'borndate'=> 'required',
        ]);
        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }
    


        $user = User::create($registrasiData);
        return response([
            'message' => 'Register Success',
            'user' => $user
        ],200);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'username' => 'required|max:60',
            'password'=> 'required'
        ]);

        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }

        

        $user = User::where('username', $loginData['username'])->where('password', $loginData['password'])->get();


        return response([
            'message'=> 'Authenticated',
            'user'=> $user,
        ]);
    }
    public function index(){
        $user = User::all();

        if(count($user) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data'=> $user
                ],200);
        }
        return response([
            'message' => 'empty',
            'data'=> null
            ],400);
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'User Is Found',
                'data' => $user
            ], 200);
        }
        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 400);
    }
    public function update(Request $request, string $id){
        $user = User::find($id);
       
        if(is_null($user)) {
            return response([
                'message'=> 'User Not Found',
                'data'=> null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'username' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'notelp' => 'required|regex:/^08[0-9]{9,11}$/',
            'borndate'=> 'required',
        ]);

        if( $validate->fails())

       

            return response(['message' => $validate->errors()],400);

            


            $user->username = $updateData['username'];
            $user->email = $updateData['email'];
            $user->password = $updateData['password'];
            $user->notelp = $updateData['notelp'];
            $user->borndate = $updateData['borndate'];

        if( $user->save() ){
            return response([
                'message' => 'Update User Succes',
                'data'=> $user
                ] ,200);
        }
            return response([
                'message' => 'Update User Fail',
                'data'=> null
                ] ,400);
    }

    public function destroy(string $id){
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message'=> 'User Not Found',
                'data'=> null
                ] ,400);
        }
        if( $user->delete() ){
            return response([
                'message' => 'Delete User Success',
                'data'=> $user
                ] ,200);
        }
        return response([
            'message'=> 'Delete User Failed',
            'data'=> null
            ] ,400);
    }
}
