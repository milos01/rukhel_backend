<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25-Oct-18
 * Time: 2:45 PM
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    public function search(string $query = ""): Collection;

    public function getById(int $id): Collection;

    public function getByUsername(string $username = ""): Collection;
}