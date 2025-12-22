<?php
namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController {
    public function register() {
        // Correction de la casse : Auth/register au lieu de auth/register
        return view('Auth/register', ['title' => 'Inscription']);
    }

    public function attemptRegister() {
        $model = new UserModel();
        // ... votre logique d'insertion ...
        
        if ($model->insert($data)) {
            // Utiliser le nom de la route 'login'
            return redirect()->route('login')->with('msg', 'Inscription réussie !');
        }
        return redirect()->back()->with('error', 'Erreur lors de l\'inscription.');
    }

    public function login() {
        // Correction de la casse : Auth/login
        return view('Auth/login', ['title' => 'Connexion']);
    }

    public function attemptLogin() {
        // ... votre logique de connexion ...
        if ($user && password_verify($password, $user['password'])) {
            // ... set session ...
            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Identifiants invalides.');
    }

    public function logout() {
        session()->destroy();
        return redirect()->route('home');
    }
}
