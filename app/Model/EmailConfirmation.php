<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    protected $table = "email_confirmation";

    protected $fillable = [
        "user_id",
        "token",
        "expires_at"
    ];

    protected $dates = [
        "expires_at"
    ];
}
