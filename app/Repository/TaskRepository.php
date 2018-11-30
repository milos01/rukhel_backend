<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Nov-18
 * Time: 4:07 PM
 */

namespace App\Repository;


use Illuminate\Database\Eloquent\Collection;

interface TaskRepository
{
    public function search(string $query = "", array $termList = []): Collection;
}