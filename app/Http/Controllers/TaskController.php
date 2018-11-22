<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\CategoryService;
use App\Services\TaskService;
use App\Util\HttpResponse;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{
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

    public function getAssigned(Request $request){
        dd("a");
    }
}
