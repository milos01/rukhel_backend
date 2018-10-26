<?php

namespace App\Providers;

use App\Repository\Elasticsearch\ElasticsearchUserRepository;
use App\Repository\Eloquent\EloquentUserRepository;
use App\Repository\UserRepository;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton(UserRepository::class, function($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (!config('services.search.enabled')) {
                return new EloquentUserRepository();
            }

            return new ElasticsearchUserRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
