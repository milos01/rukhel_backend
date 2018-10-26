<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25-Oct-18
 * Time: 2:24 PM
 */
namespace App\Model;

use App\Observers\ElasticsearchObserver;

trait Searchable {

    public static function bootSearchable()
    {
        if (config('services.search.enabled')) {
            //static:: calls observe static method from class that called this trait/class
            //self:: would call static method form this class/train
            static::observe(ElasticsearchObserver::class);
        }
    }

    public function getSearchIndex()
    {
        return $this->getTable();
    }

    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }

        return $this->getTable();
    }

    public function toSearchArray()
    {
        // By having a custom method that transforms the model
        // to a searchable array allows us to customize the
        // data that's going to be searchable per model.
        return $this->toArray();
    }
}