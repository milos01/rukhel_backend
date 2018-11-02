<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UserRequest;
use App\Model\Enums\UserType;
use App\Model\User;
use App\Services\UserService;
use App\Util\HttpResponse;
use App\Util\Traits\Collectable;
use Exception;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;



class UserController extends Controller
{
    use Collectable;

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

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

    public function changePassword(PasswordRequest $request){
        try{
            User::where("id", $request->user()->id)->update([
                "password" => bcrypt($request->new_password)
            ]);
            return response("", 200);
        }catch (Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function getUserByUsername($username){
        try{
            $user = User::where("username", $username)->get();
            return response($user, 200);
        }catch (Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function deleteUserByUsername($username){
        try{
            $this->userService->deleteUserByUsername($username);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function getStaff(Request $request){
        try{
            $moderators = User::where("role", UserType::MODERATOR())->orWhere("role", UserType::ADMIN())->get();
            return response($moderators, 200);
        }catch (Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function findUsers(Request $request, UserRepository $repository){
        try{
            $users = $repository->search($request->term)->forPage($request->page, env("USER_PER_PAGE"));

            return response($this->makeCollection($users, [
                "total" => $users->count()
            ]), 200);
        }catch (Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function upgradeToAdmin($username){
        try{
            $this->userService->upgradeToAdmin($username);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function downgradeToModerator($username){
        try{
            $this->userService->downgradeToModerator($username);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function activateUser($username){
        try{
            $this->userService->activateUser($username);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function addStaff(SignupRequest $request){
        try{
            $this->userService->addStaff($request);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception), $exception->getStatusCode());
        }
    }

}
