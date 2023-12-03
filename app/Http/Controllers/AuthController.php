<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use function Laravel\Prompts\error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(LoginRequest $req) {
        // Check if user with request email exists and if it does compare passwords
        if (Auth::attempt(['email' => $req->email,'password' => $req->password])) {
            // Create a new session with the user id in it
            $req->session()->regenerate();

            return redirect('/');
        }
    }

    public function register()
    {
        return view('register');
    }

    public function store(RegisterRequest $req) {
        // Create user in database
        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = $req->password;
        $user->save();
        // Create directory for new user
        Storage::disk('local')->makeDirectory($user->id);;
        return redirect('login');
    }

    public function logout(Request $req) {
        // delete session
        $req->session()->invalidate();
        return redirect('login');
    }
}
