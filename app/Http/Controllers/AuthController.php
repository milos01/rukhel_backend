<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Oct-18
 * Time: 3:59 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\SigninRequest;
use App\Services\AuthService;
use App\Util\HttpResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authSerice;

    public function __construct(AuthService $authSerice)
    {
        $this->authSerice = $authSerice;
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

}