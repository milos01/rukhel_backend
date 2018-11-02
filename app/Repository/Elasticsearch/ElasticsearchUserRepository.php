<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25-Oct-18
 * Time: 2:48 PM
 */

namespace App\Repository\Elasticsearch;

use App\Model\User;
use Elasticsearch\Client;
use App\Repository\UserRepository;
use Illuminate\Database\Eloquent\Collection;


class ElasticsearchUserRepository implements UserRepository
{

    private $search;

    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search(string $query = ""): Collection {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query): array {

        $instance = new User;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    "query_string" => [
                        "fields" => ["full_name^2", "username"],
                        "query" => $query . "*",
                        "allow_leading_wildcard" => false,
                        'fuzziness' => 'AUTO',
                    ]
                ]
            ]
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection {
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];
        // We have to convert the results array into Eloquent Models.
        return User::hydrate($hits);
    }

    public function getById(int $id): Collection
    {
        // TODO: Implement getById() method.
    }

    public function getByUsername(string $username = ""): Collection
    {
        // TODO: Implement getByUsername() method.
    }
}