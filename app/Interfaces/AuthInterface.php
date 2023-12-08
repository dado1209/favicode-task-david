<?php

namespace App\Interfaces;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
interface AuthInterface
{
    public function authenticate(LoginRequest $req);
    public function store(RegisterRequest $req);
}
