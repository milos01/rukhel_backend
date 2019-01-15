<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Http\Requests\TaskRequest;
use App\Model\Category;
use App\Model\Task;
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
            $category = $this->categoryService->findDependentCategoryIdByName($request->category);

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

    public function acceptUserOffer($id, $offer_id){
        try{
            $task = $this->taskService->assignUserToTask($id, $offer_id);
            return response($task, 200);
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
            $category_id = $this->categoryService->findCategoryIdByName($request->category_id);
            $request["category_id"] = $category_id;

            $pageFromRequest = $request->page;
            $filterList = $this->getFilterList($request->all());
            $termList = $this->generateTerms($filterList);

            $tasks = $repository->search($request->term, $termList);
            $totalTasks = $tasks->count();

            $tasks = $tasks->forPage($pageFromRequest, env("USER_PER_PAGE"));

            $arrayItems = $this->makeCollection($tasks, [
                "page" => $pageFromRequest,
                "total" => $totalTasks,
                "total_on_page" => env("USER_PER_PAGE")
            ]);

            return response($arrayItems, 200);
        }catch(HttpException $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function postOffer($id, OfferRequest $request){
        try{
            $task = Task::findById($id);

            $this->taskService->attachUserToTask($task, $request);
            $this->taskService->updateBestOffer($task);

            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function declineUserOffer($id, $user_id){
        try{
            $task = $this->taskService->declineOffer($id, $user_id);
            return response($task, 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }
}
