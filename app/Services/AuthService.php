<?php

namespace App\Services;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\AuthInterface;

class AuthService implements AuthInterface {
    // Check if user password matches
    public static function authenticate(LoginRequest $req) {
        if (!Auth::attempt(['email' => $req->email,'password' => $req->password])) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public static function store(RegisterRequest $req) {
        // Create user in database
        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = $req->password;
        $user->save();
        // Create directory for new user
        Storage::disk('local')->makeDirectory($user->id);;
    }
}
