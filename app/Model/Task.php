<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use SoftDeletes, Searchable, BaseModel;

    protected $table = "tasks";

    protected $fillable = [
        'subject',
        'slug',
        'description',
        'user_creator_id',
        'user_solver_id',
        'category_id',
        'solution_description',
        'time_ends_at',
        'status',
        'biding_expires_at',
    ];

    protected $dates = [
        'biding_expires_at',
    ];

    protected $hidden = [
        'pivot'
    ];

    /**
 * Get the user that created task.
 */
    public function userCreator(){
        return $this->belongsTo("App\Model\User");
    }

    /**
     * Get the user that is main solver on task.
     */
    public function userSolver(){
        return $this->belongsTo("App\Model\User");
    }

    /**
     * Get the category of task.
     */
    public function category(){
        return $this->belongsTo("App\Model\Category");
    }

    /**
     * Get all users assigned to tasks.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_task', 'task_id', 'user_id');
    }
}
