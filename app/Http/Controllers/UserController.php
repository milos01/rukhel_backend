<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Model\User;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    public function updateUser(UserRequest $request){
        try {
            User::where("id", $request->user()->id)->update($request->all());
            return response("", 200);
        }
        catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
