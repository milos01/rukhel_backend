<?php

namespace App\Services;

use App\Model\EmailConfirmation;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HashService {
    public function findHash($hash){
        $hash = EmailConfirmation::where('token', $hash)->first();

        if (is_null($hash)) {
            throw new HttpException(404, "Link not valid.");
        }

        if (Carbon::parse($hash->expires_at)->diffInMinutes(Carbon::now(), false) > 0) {
            throw new HttpException(422, "Link expired.");
        }
    }

    public function findUserByHash($token){
        return EmailConfirmation::where("token", $token)->first()->user()->first()->id;

    }
}