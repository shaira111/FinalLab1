<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponser;
use DB;

Class UserController extends Controller {
    use ApiResponser; // <- successResponse

    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function getUsers(){    
        // $users = User::all(); // eloquent style

        $users = DB::connection('mysql')     // using raw SQL queries 
        ->select("Select * from tbluser");

        // return response()->json(['data' => $users], 200); // old and redundant code
        return $this->successResponse($users);
    }

    public function index(){
        $users = User::all();
        return $this->successResponse($users);
    }   

    public function addUser(Request $request ){ 
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female'
        ];

        $this->validate($request,$rules);

        $user = User::create($request->all());
        // return $this->json($user, 200);
        // return response()->json($user, 200);
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function showUserID($id){
        $user = User::findOrFail($id);
        return $this->successResponse($user);

        // $user = User::where('user_id', $id)->first();
        // if($user){
        //     return $this->successResponse($user);
        // }
        // {
        //     return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
        // }
    }

    public function updateUser(Request $request, $id) { 
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female'
        ];
    
        $this->validate($request, $rules);
        $user = User::findOrFail($id);
    
        $user->fill($request->all());
    
        if ($user->isClean()) {
            return $this->errorResponse("At least one value must change", 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        } 

            $user->save();
            return $this->successResponse($user);
    }

    public function deleteUser($id) { 
        $user = User::findOrFail($id);
        $user->delete();
        return $this->successResponse('Successfully Removed');
    }
}