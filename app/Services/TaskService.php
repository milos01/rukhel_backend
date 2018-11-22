<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15-Nov-18
 * Time: 5:00 PM
 */

namespace App\Services;


use App\Model\Category;
use App\Model\Enums\TaskType;
use App\Model\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{
    use UtilService;

    public function addTask(Request $request, Category $category){

        Task::create([
            "subject" => $request->subject,
            "slug" => str_slug($request->subject, "-"),
            "user_creator_id" => $request->user()->id,
            "category_id" => $category->id,
            "description" => $request->description,
            "solution_description" => $request->solution_description,
            "status" => "some status",
            "biding_expires_at" => $this->getTimeNow(),
        ]);
    }

    public function getTaskById($id){
        $task = Task::findById($id);

        return $task;
    }

    public function takeTaskById($id, Request $request){
        $task = Task::findById($id);

        $task->update([
            "status" => TaskType::SOLVING(),
            "user_solver_id" => $request->user()->id
        ]);
    }

    public function inactiveTask($id){
        $task = Task::findById($id);

        $task->delete();
    }
}