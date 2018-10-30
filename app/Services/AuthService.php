<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29-Oct-18
 * Time: 4:23 PM
 */

namespace App\Services;


use App\Exceptions\InvalidCredentialsException;
use App\Model\User;
use App\Util\HttpResponse;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class AuthService
{
    private $apiConsumer;

    private $cookie;

    private $db;

    public function __construct(Application $app) {
        $this->apiConsumer = $app->make('apiconsumer');
        $this->cookie = $app->make('cookie');
        $this->db = $app->make('db');
    }

    public function attemptLogin($email, $password)
    {
        $user = User::where("email", $email)->first();

        if (!is_null($user)) {
            return $this->proxy("password", [
                "username" => $email,
                "password" => $password
            ]);
        }

        throw new InvalidCredentialsException("Invalid credentials.");
    }

    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [

                "client_id"     => env("HOST_CLIENT_ID"),
                "client_secret" => env("HOST_CLIENT_SECRET"),
                "grant_type"    => $grantType

        ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            throw new InvalidCredentialsException("Invalid token request.");
        }

        $data = json_decode($response->getContent());

        $this->cookie->queue(
            'refreshToken',
            $data->refresh_token,
            864000,
            null,
            null,
            false,
            true
        );

        return [
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in
        ];
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget("refreshToken"));
    }

}