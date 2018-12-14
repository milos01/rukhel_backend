<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Nov-18
 * Time: 3:04 PM
 */

namespace App\Repository\Elasticsearch;


use App\Model\Task;
use App\Repository\TaskRepository;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchTaskRepository implements TaskRepository
{

    private $search;

    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search(string $query = "", array $termList = []): Collection
    {
        $items = $this->searchOnElasticsearch($query, $termList);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query, array $termList): array {
        $instance = new Task();

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => $this->filterQuery($query, $termList)
        ]);

        return $items;
    }

    private function filterQuery($query, $termList){
        if ($query === "all"){
            return [
                "query" => [
                    "bool" => [
                        "must" => [
                            $termList
                        ]
                    ]
                ],
                "sort" => [
                    "id" => ["order" => "asc"]
                ]
            ];
        } else{
            return [
                "query" => [
                    "bool" => [
                        "must" => [
                            "query_string" => [
                                "fields" => ["subject^2", "description"],
                                "query" =>  $query. "*",
                                "allow_leading_wildcard" => false,
                                'fuzziness' => 'AUTO',
                            ]
                        ],
                        "filter" => $termList
                    ]
                ]
            ];
        }
    }

    private function buildCollection(array $items): Collection {
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];
        // We have to convert the results array into Eloquent Models.
        return Task::hydrate($hits);
    }
}