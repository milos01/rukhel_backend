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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{
    use UtilService;

    public function addTask(Request $request, $category)
    {
        Task::create([
            "subject" => $request->subject,
            "slug" => str_slug($request->subject, "-"),
            "user_creator_id" => $request->user()->id,
            "category_id" => $category->id,
            "description" => $request->description,
            "biding_expires_at" => Carbon::now()->addMinutes(env("BID_EXPIRE_MINUTES")),
            "status" => "some status",
            "categories" => $category
        ]);
    }

    public function getTaskById($id)
    {
        $task = Task::findById($id);

        return $task;
    }

    public function takeTaskById($id, Request $request)
    {
        $task = Task::findById($id);

        $task->update([
            "status" => TaskType::SOLVING(),
            "user_solver_id" => $request->user()->id
        ]);
        //TODO find user by offer
    }

    public function inactiveTask($id)
    {
        $task = Task::findById($id);

        $task->delete();
    }

    public function getAssignedUsers($id)
    {
        $task = Task::findById($id);

        return $task->users;
    }

    public function assignUserToTask($id, $user_id)
    {
        $task = Task::findById($id);

        $task->update([
            "user_solver_id" => $user_id
        ]);
    }

    public function dismissUserToTask($id, $user_id)
    {
        $task = Task::findById($id);

        $exist = $task->users()->where("user_id", $user_id)->exists();

        if (!$exist) {
            throw new HttpException(422, "User not assigned to task.");
        }

        $task->users()->detach($user_id);
    }

    public function resetTaskExpire($id)
    {
        $task = Task::findById($id);

        $task->update([
            "biding_expires_at" => $this->getTimeNow()->addMinutes(env("BID_EXPIRE_MINUTES")),
            "status" => TaskType::WAITING(),
        ]);
    }

    public function attachUserToTask($task, $request)
    {


        $exist = $task->users()->where("user_id", $request->user()->id)->exists();

        if ($exist) {
            throw new HttpException(422, "User offered on task.");
        }

        $task->users()->attach($request->user()->id, ["offer" => $request->offer]);
    }

    public function updateBestOffer($task)
    {
        $minimaOffer = $this->findMinimaOffer($task);

        $task->update([
            "status" => TaskType::OFFERED(),
            "best_offer" => json_encode($minimaOffer)
        ]);
    }

    private function findMinimaOffer($task)
    {
        $minima = PHP_INT_MAX;
        foreach ($task->users as $user) {
            $taskOffer = $user->pivot->offer;
            if ($taskOffer < $minima) {
                $id = $user->pivot->id;
                $minima = $taskOffer;
                $offerCreated = $user->pivot->created_at;
            }
        }

        return [
            "id" => $id,
            "created_at" => $offerCreated->toDateTimeString(),
            "offer" => $minima
        ];
    }

    public function updateTaskFiles($file, $id)
    {
        $task = Task::findById($id);
        $tempArray = json_decode($task->testcol);

        $data = [
            "id" => $task->id,
            "hash_name" => $file->hash_name
        ];

        array_push($tempArray, $data);
        Task::findById($id)->update([
            "testcol" => json_encode($tempArray)
        ]);
    }
}