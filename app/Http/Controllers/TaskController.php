<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Repository\TaskRepository;
use App\Services\CategoryService;
use App\Services\TaskService;
use App\Services\UtilService;
use App\Util\HttpResponse;
use App\Util\Traits\Collectable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{
    use Collectable, UtilService;

    private $taskService;
    private $categoryService;

    public function __construct(TaskService $taskService, CategoryService $categoryService){
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
    }

    public function addTask(TaskRequest $request){
        try{
            $category = $this->categoryService->findCategoryById($request->category_id);

            $this->taskService->addTask($request, $category);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function getTask($id){
        try{
            $task = $this->taskService->getTaskById($id);
            return response($task, 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function takeTask($id, Request $request){
        try{
            $this->taskService->takeTaskById($id, $request);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function inactiveTask($id){
        try{
            $this->taskService->inactiveTask($id);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function getAssignedUsers($id){
        try{
            $users = $this->taskService->getAssignedUsers($id);
            return response($users, 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function assignUserToTask($id, $user_id){
        try{
            $this->taskService->assignUserToTask($id, $user_id);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function dismissUserToTask($id, $user_id){
        try{
            $this->taskService->dismissUserToTask($id, $user_id);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }

    }

    public function resetTaskExpire($id){
        try{
            $this->taskService->resetTaskExpire($id);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function getAllTasks(Request $request, TaskRepository $repository){
        try{
            $filterList = $this->getFilterList($request->all());

            $termList = $this->generateTerms($filterList);

            $tasks = $repository->search($request->term, $termList)->forPage($request->page, env("USER_PER_PAGE"));

            return response($this->makeCollection($tasks, [
                "total" => $tasks->count()
            ]), 200);
        }catch(HttpException $exception){
            return response($exception->getMessage(), 500);
        }
    }
}
