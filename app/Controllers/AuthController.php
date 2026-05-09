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
            'username' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
                'regex_match' => 'Password must contain at least one number and one special character (!@#$%^&* etc).'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
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
            'password'         => 'required|min_length[8]|regex_match[/[0-9]/]|regex_match[/[!@#$%^&*()_+\-=\[\]{};:\'",.\/<>?]/]',
            'confirm_password' => 'matches[password]'
        ];

        $messages = [
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
                'regex_match' => 'Password must contain at least one number and one special character (!@#$%^&* etc).'
            ],
            'confirm_password' => [
                'matches' => 'Passwords do not match.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
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
