<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes, BaseModel;

    protected $table = "files";

    protected $fillable = [
        "hash_name", "user_id", "task_id"
    ];

    /**
     * Get the user related to file.
     */
    public function user(){
        return $this->belongsTo("App\Model\User");
    }

    /**
     * Get the task related to file.
     */
    public function task(){
        return $this->belongsTo("App\Model\Task");
    }
}
