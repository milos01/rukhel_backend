<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19-Nov-18
 * Time: 1:24 PM
 */

namespace App\Model;

use Symfony\Component\HttpKernel\Exception\HttpException;

trait BaseModel
{
    public static function findById($id){
        if (static::class === Task::class){
            $task = static::with("userCreator:id,full_name,username", "category:id,name,display_name", "userSolver:id,full_name,username")->where("id", $id)->first();
        }else{
            $task = static::where("id", $id)->first();
        }

        if(is_null($task)){
            throw new HttpException(404, self::getShortName()." not found.");
        }

        return $task;
    }

    private static function getShortName(){
        return substr(strrchr(static::class, "\\"), 1);
    }
}