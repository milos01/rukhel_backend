<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use SoftDeletes, BaseModel;

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
        'status'
    ];

    protected $hidden = [

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
        return $this->belongsToMany('App\Problem', 'user_problem', 'user_id', 'task_id')->withTimestamps();
    }
}
