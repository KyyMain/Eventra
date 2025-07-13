<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') === 'admin' ? '/admin/dashboard' : '/user/dashboard');
        }

        $data = [
            'title' => 'Login - Eventra'
        ];

        return view('auth/login', $data);
    }

    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_active']) {
                return redirect()->back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
            }

            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
            } else {
                return redirect()->to('/user/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
            }
        } else {
            return redirect()->back()->with('error', 'Email atau password salah.');
        }
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') === 'admin' ? '/admin/dashboard' : '/user/dashboard');
        }

        $data = [
            'title' => 'Register - Eventra'
        ];

        return view('auth/register', $data);
    }

    public function processRegister()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'full_name' => 'required|min_length[3]|max_length[255]',
            'phone' => 'permit_empty|min_length[10]|max_length[20]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'role' => 'user',
            'is_active' => true
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat registrasi.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout.');
    }

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Profile - Eventra',
            'user' => $user
        ];

        return view('auth/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'full_name' => 'required|min_length[3]|max_length[255]',
            'phone' => 'permit_empty|min_length[10]|max_length[20]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone')
        ];

        // Update password if provided
        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
            }
            $userData['password'] = $newPassword;
        }

        if ($this->userModel->update($userId, $userData)) {
            // Update session data
            session()->set([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'full_name' => $userData['full_name']
            ]);

            return redirect()->back()->with('success', 'Profile berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat update profile.');
        }
    }
}