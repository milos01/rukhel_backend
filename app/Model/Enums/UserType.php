<?php

namespace App\Model\Enums;

use \MyCLabs\Enum\Enum;

/**
 * @method static Action USER()
 * @method static Action MODERATOR()
 * @method static Action ADMIN()
 */
class UserType extends Enum {
    private const USER = 'USER';
    private const MODERATOR = 'MODERATOR';
    private const ADMIN = 'ADMIN';
}
