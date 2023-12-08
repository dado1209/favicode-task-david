<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $req, AuthInterface $userService)
    {
        // LoginRequest handles checking if user exists
        try {
            $userService->authenticate($req);
            $req->session()->regenerate();
            return redirect('/');
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            Log::error('Authentication Exception : ' . $e->getMessage());
            return redirect()->back()->withErrors(['message' => 'Invalid credentials']);
        } catch(\Exception $e) {
            Log::error('Exception : ' . $e->getMessage());
            return view('errors.error')->with(['message' => 'Something went wrong']);
        }
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $req, AuthInterface $userService)
    {
        try {
            $userService->store($req);
            return redirect('login');
        } catch (\Exception $e) {
            Log::error('Exception : ' . $e->getMessage());
            return redirect()->back()->withErrors(['message' => 'Something went wrong']);
        }
    }

    public function logout(Request $req, AuthInterface $service)
    {
        try{
            // delete session
            $req->session()->invalidate();
            return redirect('login');
        }
        catch(\Exception $e) {
            Log::error('Exception : ' . $e->getMessage());
            return view('errors.error')->with(['message' => 'Something went wrong']);
        }
    }
}
