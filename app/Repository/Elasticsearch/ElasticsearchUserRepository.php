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

        dd($items);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query): array {

        $instance = new User;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['email', 'username'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];

        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.
            $source['tags'] = json_encode($source['tags']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return User::hydrate($sources);
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