<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Exception;

class WrongPasswordException extends AuthenticationException
{
    //
}
