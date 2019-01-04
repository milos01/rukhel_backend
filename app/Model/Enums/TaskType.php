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
 * @method static Action SOLVING()
 * @method static Action SOLVED()
 * @method static Action NOOFFER()
 * @method static Action PAID()
 * @method static Action WAITING()
 * @method static Action EXPIRED()
 * @method static Action OFFERED()
 */
class TaskType extends Enum
{
    private const SOLVING = 'SOLVING';
    private const SOLVED = 'SOLVED';
    private const NOOFFER = 'NOOFFER';
    private const PAID = 'PAID';
    private const WAITING = 'WAITING';
    private const EXPIRED = 'EXPIRED';
    private const OFFERED = 'OFFERED';
}