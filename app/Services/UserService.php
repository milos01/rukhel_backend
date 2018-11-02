<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01-Nov-18
 * Time: 4:56 PM
 */

namespace App\Services;


use App\Model\Enums\UserType;
use App\Model\User;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;


class UserService
{
    public function deleteUserByUsername($username){
        $user = $this->findUserByUsername($username);

        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->where("revoked", false);

        if ($tokens->get()->isEmpty()){
            throw new HttpException(404, "No token for user.");
        }

        $user->delete();
        $tokens->update([
            "revoked" => true
        ]);

    }

    public function activateUser($username){
        $user = User::onlyTrashed()->where("username", $username)->first();

        if(is_null($user)){
            throw new HttpException(404, "Deleted user not found.");
        }

        $user->restore();
    }

    public function upgradeToAdmin($username){
        $user = $this->findUserByUsername($username);

        if ($user->role == UserType::ADMIN()->getValue()){
            throw new HttpException(400, "Already upgraded.");
        }

        $user->update([
            "role" => UserType::ADMIN()
        ]);
    }

    public function downgradeToModerator($username){
        $user = $this->findUserByUsername($username);

        if($user->role == UserType::MODERATOR()){
            throw new HttpException(400, "Already downgraded.");
        }

        $user->update([
            "role" => UserType::MODERATOR()
        ]);
    }

    public function addStaff($request){
        User::create([
            "full_name" => $request->full_name,
            "email" => $request->email,
            "username" => $request->username,
            "password" => bcrypt($request->password),
            "role" => UserType::USER(),
            "activated" => 0,
        ]);
    }

    private function findUserByUsername($username){
        $user = User::where("username", $username)->first();

        if (is_null($user)){
            throw new HttpException(404, "User not found.");
        }

        return $user;
    }
}