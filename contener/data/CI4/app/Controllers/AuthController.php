<?php

namespace App\Controllers;

use App\Models\UserModel;

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
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->to('/login')->with('error', 'Champs manquants.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', strtolower($email))->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Identifiants invalides.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Identifiants invalides.');
        }


        // On set la session (compatible avec ton layout)
        session()->set([
            'user_id'    => (int) $user['id'],
            'username'   => (string) $user['username'],
            'role'       => (string) ($user['role'] ?? 'user'),
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/');
    }

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

        if ($username === '' || $email === '' || $password === '' || $confirm === '') {
            return redirect()->to('/register')->with('error', 'Champs manquants.');
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

        // (optionnel) vérifier username unique
        $existingUser = $userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/register')->with('error', 'Pseudo déjà utilisé.');
        }

        $data = [
            'email'         => $email,
            'username'      => $username,
            'password_hash' => hash('sha256', $password),
            'role'          => 'user',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $userModel->insert($data);
        $newId = (int) $userModel->getInsertID();

        // auto-login après register (comme beaucoup de TD)
        session()->set([
            'user_id'    => $newId,
            'username'   => $username,
            'role'       => 'user',
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        // version TD : on vide les clés
        session()->remove(['user_id', 'username', 'role', 'isLoggedIn']);
        return redirect()->to('/');
    }
}
