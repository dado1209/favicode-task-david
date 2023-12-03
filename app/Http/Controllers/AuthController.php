<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function postLogin(LoginRequest $req) {
        $user = User::where('email', $req->email)->first();
        // Check if user exists
        if (!$user) {return redirect()->back()->withErrors(['email' => 'Invalid credentials']);}
        // Check if password matches
        if (!(Hash::check($req->input('password'), $user->password))) {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }
        // Authenticate user
        $req->session()->put('userId', $user->id);
        return redirect('/');
    }

    public function register()
    {
        return view('register');
    }

    public function postRegister(RegisterRequest $req) {
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
