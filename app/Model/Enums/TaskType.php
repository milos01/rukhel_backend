<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15-Nov-18
 * Time: 4:11 PM
 */

namespace App\Model\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Action WAITING()
 * @method static Action SOLVING()
 * @method static Action EXPIRED()
 */
class TaskType extends Enum
{
    private const SOLVING = 'SOLVING';
    private const WAITING = 'WAITING';
    private const EXPIRED = 'EXPIRED';
}