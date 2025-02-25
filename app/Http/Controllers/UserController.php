<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

//use Illuminate\Hashing\BcryptHasher;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'tel' => 'max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'role' => 'Utilisateur',
            'password' => bcrypt($data['password']),
        ]);
        Log::info('Un nouvel utilisateur a été ajouté avec l\'Email : ' . $user->email);
        
        return redirect('/')->with('message', 'Bienvenue dans notre site, Veuillez vous connecter en utilisant le bouton login');
    }

    public function login(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            $hasher = app('hash');
            if ($hasher->check($data['password'], $user->password)) {
                Auth::login($user);
                session()->put('username', $user->nom . ' ' . $user->prenom);
                Log::info('L\'utilisateur ' . $user->email . ' est connecté depuis l\'adresse IP: ' . request()->ip());
                return redirect('/')->with('user', $user);
            } else {
                return redirect('/login')->with('message', 'Vérifier votre mot de passe');
            }
        } else {
            return redirect('/login')->with('message', 'Vérifier votre Email');
        }
    }

    public function logout(Request $request)
    {
        Log::warning('L\'utilisateur ' . Auth::user()->email . ' est déconnecté depuis l\'adresse IP: ' . request()->ip());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message', 'Vous avez été déconnecté avec succès.');
    }
}
