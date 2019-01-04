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

    public function getFilterList($params){
        if (isset($params["term"]) && isset($params["page"])){
            unset($params["term"]);
            unset($params["page"]);
        }

        if (isset($params["status"]) && $params["status"] === "ALL") {
            unset($params["status"]);
        }

        return $params;
    }

    public function generateTerms($filterList){
        $terms = [];

        foreach ($filterList as $filter){
            $term = [];
            $termItem = [];

            $key = array_search($filter, $filterList);

            $termItem[$key] = $filter;

            $term["match"] = $termItem;


            array_push($terms,$term);
        }

        return $terms;
    }
}