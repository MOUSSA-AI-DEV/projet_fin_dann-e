<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Votre compte est desactive. Veuillez contacter l\'administrateur.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom'              => ['required', 'string', 'max:100'],
            'prenom'           => ['required', 'string', 'max:100'],
            'email'            => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'telephone'        => ['nullable', 'string', 'max:20'],
            'adresse_livraison'=> ['nullable', 'string', 'max:255'],
            'code_postal'      => ['nullable', 'string', 'max:10'],
            'ville'            => ['nullable', 'string', 'max:100'],
        ]);

        $role = User::count() === 0 ? 'admin' : 'user';

        $user = User::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'telephone'         => $validated['telephone'] ?? null,
            'adresse_livraison' => $validated['adresse_livraison'] ?? null,
            'code_postal'       => $validated['code_postal'] ?? null,
            'ville'             => $validated['ville'] ?? null,
            'password'          => Hash::make($validated['password']),
            'role'              => $role,
            'is_active'         => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
