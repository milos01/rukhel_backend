<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Oct-18
 * Time: 3:59 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\CheckHashRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ResetPassword;
use App\Model\EmailConfirmation;
use App\Services\AuthService;
use App\Services\HashService;
use App\Services\UserService;
use App\Util\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    private $authSerice;

    private $userService;

    private $hashService;

    public function __construct(AuthService $authSerice, UserService $userService, HashService $hashService)
    {
        $this->authSerice = $authSerice;
        $this->userService = $userService;
        $this->hashService = $hashService;
    }

    public function logout(Request $request)
    {
        $this->authSerice->logout($request);

        return response(null, 204);
    }

    public function signin(SigninRequest $request){
        try{
            return response($this->authSerice->attemptLogin($request->email, $request->password), 200);
        }catch (InvalidCredentialsException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function sendResetLink(ResetPasswordRequest $request){
        try{
            $user = $this->userService->findUserByEmail($request->email);
            $link = $this->authSerice->generateResetLink($user);
            Mail::to("milosa942@gmail.com")->send(new ResetPassword($link));
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function checkHash(CheckHashRequest $request){
        try{
            $this->hashService->findHash($request->token);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request){
        try{
            $id = $this->hashService->findUserByHash($request->token);
            $this->authSerice->updatePassword($id,$request->password);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

}