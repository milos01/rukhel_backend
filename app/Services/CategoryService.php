<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15-Nov-18
 * Time: 2:49 PM
 */

namespace App\Services;


use App\Model\Category;
use App\Model\Task;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService
{
    public function deleteCategoryByName($name){
        $category = Category::where("name", $name)->first();

        if (is_null($category)){
            throw new HttpException(404, "No category found.");
        }

        $category->delete();
    }

    public function activateCategoryByName($name){
        $category = Category::onlyTrashed()->where("name", $name)->first();

        if(is_null($category)){
            throw new HttpException(404, "Deleted category not found.");
        }

        $category->restore();
    }

    public function findCategoryById($id){
        $category = Category::where("id", $id)->first();

        if(is_null($category)){
            throw new HttpException(404, "Category not found.");
        }

        return $category;
    }

    public function findCategoryIdByName($name){
        $category = Category::where("name", $name)->first();

        if (is_null($category)){
            throw new HttpException(404, "Category not found.");
        }

        return $category->id;
    }

    public function findDependentCategoryIdByName($name){
        $category = Category::select("id", "name", "display_name", "color")->where("name", $name)->first();

        if (is_null($category)){
            throw new HttpException(404, "Category not found.");
        }

        return $category;
    }
}