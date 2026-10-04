<?php

namespace App\Controllers;

use App\Models\CustomerAccount;
use CodeIgniter\HTTP\RedirectResponse;

class Register extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to($this->dashboardPath());
        }

        return view('register', [
            'title' => 'Register - Puihaha Electric',
            'page' => 'register',
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error'),
            'validation' => session()->getFlashdata('validation'),
        ]);
    }

    public function create(): RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to($this->dashboardPath());
        }

        $fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city',
            'state', 'zip_code', 'password', 'confirm_password', 'terms'];
        $input = [];
        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            $input[$field] = is_string($value) ? $value : '';
            if (! in_array($field, ['password', 'confirm_password'], true)) {
                $input[$field] = trim($input[$field]);
            }
        }
        $input['email'] = strtolower($input['email']);

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]|is_unique[customer_accounts.email]',
            'phone' => 'required|min_length[10]|max_length[20]',
            'address' => 'required|min_length[5]|max_length[255]',
            'city' => 'required|min_length[2]|max_length[100]',
            'state' => 'required|min_length[2]|max_length[50]',
            'zip_code' => 'required|min_length[4]|max_length[10]',
            'password' => 'required|min_length[8]|max_length[72]',
            'confirm_password' => 'required|matches[password]',
            'terms' => 'required|in_list[on]',
        ];

        try {
            if (! $this->validateData($input, $rules)) {
                return $this->formRedirect('/register', $input, $this->validator->getErrors());
            }

            $userData = $input;
            unset($userData['confirm_password'], $userData['terms']);
            $userData['customer_name'] = $input['first_name'] . ' ' . $input['last_name'];
            $userData['account_number'] = CustomerAccount::generateAccountNumber();
            $userData['connection_type'] = 'residential';
            $userData['status'] = 'active';
            $userData['is_active'] = true;
            $userData['email_verified'] = false;
            $model = new CustomerAccount();
            if (! $model->insert($userData)) {
                return $this->formRedirect('/register', $input, $model->errors(), 'Please check your account details.');
            }
        } catch (\Throwable $exception) {
            log_message('error', 'Registration failed: {message}', ['message' => $exception->getMessage()]);

            return $this->formRedirect('/register', $input, [], 'Registration is temporarily unavailable. Please try again.');
        }

        return redirect()->to('/login')->with('success', 'Registration successful. You can now log in.');
    }
}
