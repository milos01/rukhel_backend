<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16-Nov-18
 * Time: 10:15 AM
 */

namespace App\Services;


use Carbon\Carbon;

trait UtilService
{
    public function getTimeNow(){
        return Carbon::now();
    }
}