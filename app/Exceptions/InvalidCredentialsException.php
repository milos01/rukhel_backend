<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29-Oct-18
 * Time: 4:10 PM
 */

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InvalidCredentialsException extends UnauthorizedHttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct('', $message, $previous, $code);
    }
}