<?php

namespace App\Interfaces;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
interface AuthInterface
{
    public static function authenticate(LoginRequest $req);
    public static function store(RegisterRequest $req);
}
