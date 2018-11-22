<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Model\Category;
use App\Services\CategoryService;
use App\Util\HttpResponse;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;


class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function addCategory(CategoryRequest $request){
        try{
            Category::create($request->all());
            return response("", 200);
        }catch (Exception $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), 500);
        }
    }

    public function deleteCategory($name){
        try{
            $this->categoryService->deleteCategoryByName($name);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function activateCategory($name){
        try{
            $this->categoryService->activateCategoryByName($name);
            return response("", 200);
        }catch (HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }

    public function getCategories(){
        try{
            $categories = Category::all();
            return response($categories, 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }
}
