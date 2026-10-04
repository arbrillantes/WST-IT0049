<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\CustomerAccount;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string|RedirectResponse
    {
        return $this->loginPage(false);
    }

    public function adminLogin(): string|RedirectResponse
    {
        return $this->loginPage(true);
    }

    public function attemptLogin(): RedirectResponse
    {
        return $this->authenticate(false);
    }

    public function attemptAdminLogin(): RedirectResponse
    {
        return $this->authenticate(true);
    }

    public function dashboard(): RedirectResponse
    {
        return redirect()->to($this->dashboardPath());
    }

    public function logout(): RedirectResponse
    {
        $login = session()->get('userType') === 'admin' ? '/admin/login' : '/login';
        session()->destroy();

        return redirect()->to($login);
    }

    private function loginPage(bool $admin): string|RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to($this->dashboardPath());
        }

        return view('login', [
            'title' => ($admin ? 'Admin Login' : 'Customer Login') . ' - Puihaha Electric',
            'page' => $admin ? 'admin_login' : 'login',
            'adminLogin' => $admin,
        ]);
    }

    private function authenticate(bool $admin): RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to($this->dashboardPath());
        }

        $field = $admin ? 'username' : 'email';
        $role = $admin ? 'admin' : 'customer';
        $login = $admin ? '/admin/login' : '/login';
        $input = $this->request->getPost([$field, 'password']);
        $input[$field] = is_string($input[$field]) ? strtolower(trim($input[$field])) : '';
        $rules = [
            $field => $admin ? 'required|max_length[50]' : 'required|valid_email|max_length[100]',
            'password' => 'required|max_length[72]',
        ];

        if (! $this->validateData($input, $rules)) {
            return $this->formRedirect($login, $input, $this->validator->getErrors());
        }

        try {
            // Each endpoint uses its own identity table, never a posted role.
            $user = $admin
                ? (new User())->where('username', $input[$field])->where('user_type', 'admin')->first()
                : (new CustomerAccount())->where('email', $input[$field])->first();
        } catch (\Throwable $exception) {
            log_message('error', 'Login database error: {message}', ['message' => $exception->getMessage()]);
            return $this->formRedirect($login, $input, [], 'Login is temporarily unavailable. Please try again.');
        }

        if ($user === null || empty($user['password']) || ! password_verify($input['password'], $user['password'])) {
            return $this->formRedirect($login, $input, [], $admin ? 'Invalid admin username or password.' : 'Invalid email or password.');
        }
        if (! $user['is_active']) {
            return $this->formRedirect($login, $input, [], 'This account is inactive.');
        }

        session()->regenerate(true);
        session()->set([
            'isLoggedIn' => true,
            'userId' => (int) $user['id'],
            'userName' => $admin ? trim($user['first_name'] . ' ' . $user['last_name']) : $user['customer_name'],
            'userType' => $role,
            'identityStore' => $admin ? 'users' : 'customer_accounts',
        ]);

        return redirect()->to($this->dashboardPath())->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }
}
