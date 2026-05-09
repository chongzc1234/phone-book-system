<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        // Load form helper and session
        helper(['form', 'url']);
    }

    public function login()
    {
        // If logged in, redirect to contacts list
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/contacts');
        }
        return view('auth/login');
    }

    public function loginProcess()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Login successful, set session
            $sessionData = [
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);
            return redirect()->to('/contacts');
        } else {
            session()->setFlashdata('error', 'Invalid Username or Password');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/contacts');
        }
        return view('auth/register');
    }

    public function registerProcess()
    {
        // Strict registration form validation
        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password'         => 'required|min_length[5]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'validation' => $this->validator
            ]);
        }

        // Hash the password 
        $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'password' => $hashedPassword
        ]);

        session()->setFlashdata('success', 'Registration successful! Please login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}