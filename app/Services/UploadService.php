<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29-Nov-18
 * Time: 4:07 PM
 */

namespace App\Services;

use App\Model\File;
use App\Model\Task;

class UploadService
{
    public function addProfilePicture($hash_name, $user_id){
        File::create([
            "hash_name" => $hash_name,
            "user_id" => $user_id
        ]);
    }

    public function addTaskFile($hash_name, $task_id){
        Task::findById($task_id);

        File::create([
            "hash_name" => $hash_name,
            "task_id" => $task_id
        ]);
    }
}