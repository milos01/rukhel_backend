<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Oct-18
 * Time: 10:15 AM
 */

namespace App\Repository\Eloquent;


use App\Repository\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepository
{

    public function search(string $query = ""): Collection
    {
        // TODO: Implement search() method.
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