<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Requests\UserRequest;
use App\Model\Enums\UserType;
use App\Model\User;
use Exception;

class UserController extends Controller
{
    public function signup(SignupRequest $request){
        try{
            User::create([
                "full_name" => $request->full_name,
                "email" => $request->email,
                "username" => $request->username,
                "password" => bcrypt($request->password),
                "role" => UserType::USER(),
                "activated" => 0,
            ]);
            return response("", 200);
        }catch (Exception $exception){
            return response($exception->getMessage(), 500);
        }


    }

    public function updateUser(UserRequest $request){
        try {
            User::where("id", $request->user()->id)->update($request->all());
            return response("", 200);
        }
        catch (Exception $exception) {
            return response($exception->getMessage(), 500);
        }
    }
}
