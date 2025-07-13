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
        
        // Get current user data
        $currentUser = $this->userModel->find($userId);
        
        // Base validation rules
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'full_name' => 'required|min_length[3]|max_length[255]',
            'phone' => 'permit_empty|min_length[10]|max_length[20]'
        ];
        
        // Add password validation only if new password is provided
        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            $rules['new_password'] = 'required|min_length[8]';
            $rules['confirm_password'] = 'required|matches[new_password]';
        }
        
        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $user = $this->userModel->find($userId);
            $data = [
                'title' => 'Profile - Eventra',
                'user' => $user,
                'errors' => $validation->getErrors()
            ];
            return view('auth/profile', $data);
        }

        // Manual unique validation
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        
        // Check if username is unique (excluding current user)
        if ($username !== $currentUser['username']) {
            $existingUser = $this->userModel->where('username', $username)->where('id !=', $userId)->first();
            if ($existingUser) {
                $user = $this->userModel->find($userId);
                $data = [
                    'title' => 'Profile - Eventra',
                    'user' => $user,
                    'errors' => ['username' => 'Username sudah digunakan oleh user lain.']
                ];
                return view('auth/profile', $data);
            }
        }
        
        // Check if email is unique (excluding current user)
        if ($email !== $currentUser['email']) {
            $existingUser = $this->userModel->where('email', $email)->where('id !=', $userId)->first();
            if ($existingUser) {
                $user = $this->userModel->find($userId);
                $data = [
                    'title' => 'Profile - Eventra',
                    'user' => $user,
                    'errors' => ['email' => 'Email sudah digunakan oleh user lain.']
                ];
                return view('auth/profile', $data);
            }
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone')
        ];

        // Update password if provided
        if (!empty($newPassword)) {
            log_message('debug', 'AuthController: New password provided, length: ' . strlen($newPassword));
            $userData['password'] = $newPassword;
        } else {
            log_message('debug', 'AuthController: No new password provided');
        }

        log_message('debug', 'AuthController: Updating user data: ' . json_encode(array_keys($userData)));
        
        // Get current user data for comparison
        $currentUser = $this->userModel->find($userId);
        log_message('debug', 'AuthController: Current password hash: ' . substr($currentUser['password'], 0, 20) . '...');
        
        if ($this->userModel->update($userId, $userData)) {
            log_message('debug', 'AuthController: User update successful');
            
            // Verify password was updated if provided
            if (!empty($newPassword)) {
                $updatedUser = $this->userModel->find($userId);
                log_message('debug', 'AuthController: New password hash: ' . substr($updatedUser['password'], 0, 20) . '...');
                log_message('debug', 'AuthController: Password changed: ' . ($currentUser['password'] !== $updatedUser['password'] ? 'YES' : 'NO'));
            }
            
            // Update session data
            session()->set([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'full_name' => $userData['full_name']
            ]);

            return redirect()->to('/auth/profile')->with('success', 'Profile berhasil diupdate.');
        } else {
            log_message('error', 'AuthController: User update failed');
            log_message('error', 'AuthController: UserModel errors: ' . json_encode($this->userModel->errors()));
            log_message('error', 'AuthController: Database error: ' . json_encode($this->userModel->db->error()));
            return redirect()->to('/auth/profile')->with('error', 'Terjadi kesalahan saat update profile.');
        }
    }
}