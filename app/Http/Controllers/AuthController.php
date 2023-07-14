<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showCreateAccountForm()
    {
        return view('create-account');
    }

    public function createAccount(Request $request)
    {
        $validatedData = $request->validate([
            'account_number' => 'required|string|unique:users',
        ]);

        $user = Auth::user();
        $user->account_number = $validatedData['account_number'];
        $user->save();

        return redirect('/dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function registered(Request $request, $user)
    {
        if (!$user->account_number) {
            return redirect()->route('account.create')->with('warning', 'Please fill in your account number before proceeding.');
        }
    
        return redirect($this->redirectPath());
    }

    public function register(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        // 'account_number' => 'required|digits_between:8,255|unique:users',
    ]);

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        // 'account_number' => $validatedData['account_number'],
    ]);

    Auth::login($user);

    $request->session()->regenerate();

    return redirect('/create-account');
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
