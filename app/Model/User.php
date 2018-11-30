<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Model\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, Searchable, BaseModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'username', 'email', 'password', 'role', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'provider', 'provider_id', 'pivot'
    ];

    /**
     * Get all tasks assigned to user.
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'user_task', 'user_id', 'task_id');
    }
}
