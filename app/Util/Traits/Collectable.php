<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 31-Oct-18
 * Time: 4:55 PM
 */

namespace App\Util\Traits;


trait Collectable
{
    protected function makeCollection($items, ...$metaData){
        $collection = collect();

        $collection->put("items", $items);
        $collection->put("meta", $metaData);

        return $collection;
    }

}