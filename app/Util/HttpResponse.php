<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29-Oct-18
 * Time: 4:34 PM
 */

namespace App\Util;


class HttpResponse
{
    public static function handleResponse($message){
        return [
           "errors" => [
               "global" => $message
           ],
        ];
    }

}