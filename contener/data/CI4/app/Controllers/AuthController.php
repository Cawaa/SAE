<?php

namespace App\Controllers;

use App\Models\UserModel;

// Contrôleur pour l'authentification des utilisateurs.
class AuthController extends BaseController
{
    public function loginForm()
    {
        // si déjà connecté -> home
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/login', [
            'title'  => 'Connexion',
            'error'  => session()->getFlashdata('error'),
        ]);
    }

    public function login()
    {
        // Récupère et valide les données du formulaire envoyé en POST
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->to('/login')->with('error', 'Champs manquants.');
        }

        // Vérifie si les identifiants sont corrects
        $userModel = new UserModel();
        $user = $userModel->where('email', strtolower($email))->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Identifiants invalides.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Identifiants invalides.');
        }


        // On set la session 
        session()->set([
            'user_id'    => (int) $user['id'],
            'username'   => (string) $user['username'],
            'role'       => (string) ($user['role'] ?? 'user'),
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/');
    }

    // Affiche le formulaire d'inscription.
    public function registerForm()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/register', [
            'title' => 'Inscription',
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function register()
    {
        $username = trim((string) $this->request->getPost('username'));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $confirm  = (string) $this->request->getPost('confirm_password');
        $acceptConditions = (int) $this->request->getPost('accept_conditions');

        if ($username === '' || $email === '' || $password === '' || $confirm === '') {
            return redirect()->to('/register')->with('error', 'Champs manquants.');
        }

        if (!$acceptConditions) {
            return redirect()->to('/register')->with('error', 'Tu dois accepter les Conditions Générales d\'Utilisation.');
        }

        if ($password !== $confirm) {
            return redirect()->to('/register')->with('error', 'Les mots de passe ne correspondent pas.');
        }

        $userModel = new UserModel();

        // Vérifie email unique
        $existing = $userModel->where('email', $email)->first();
        if ($existing) {
            return redirect()->to('/register')->with('error', 'Email déjà utilisé.');
        }

        // vérifier username unique
        $existingUser = $userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/register')->with('error', 'Pseudo déjà utilisé.');
        }

        // Créer l'utilisateur
        $userId = $userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'user',
        ]);

        if (!$userId) {
            return redirect()->to('/register')->with('error', 'Erreur lors de la création du compte.');
        }

        // Connecter l'utilisateur automatiquement
        session()->set([
            'user_id'    => (int) $userId,
            'username'   => $username,
            'role'       => 'user',
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/')->with('success', 'Compte créé avec succès !');
    }

    public function logout()
    {
        // on vide les clés
        session()->remove(['user_id', 'username', 'role', 'isLoggedIn']);
        return redirect()->to('/');
    }
}
