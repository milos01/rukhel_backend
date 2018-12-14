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
    protected function makeCollection($items, $metaData)
    {
        $itemArray["items"] = [];

        foreach ($items as $item) {
            array_push($itemArray["items"], $item);
        }

        $itemArray["meta"] = $metaData;

        return $itemArray;
    }

}