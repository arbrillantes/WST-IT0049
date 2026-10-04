<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Contact extends BaseController
{
    public function index(): string|RedirectResponse
    {
        $data = [
            'title' => 'Contact Us - Puihaha Electric',
            'page' => 'contact',
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error'),
            'validation' => session()->getFlashdata('validation')
        ];
        // Handle form submission
        if ($this->request->getMethod() === 'POST') {
            return $this->submitForm();
        }
        return view('contact', $data);
    }
    private function submitForm()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'required|min_length[10]|max_length[20]',
            'service_type' => 'required',
            'message' => 'required|min_length[10]|max_length[1000]'
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('validation', $validation->getErrors());
            return redirect()->back()->withInput();
        }
        // In a real application, you would save to database or send email
        // For this demo, we'll just show a success message
        $contactData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'service_type' => $this->request->getPost('service_type'),
            'message' => $this->request->getPost('message'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        // Here you would typically:
        // 1. Save to database
        // 2. Send email notification
        // 3. Send auto-reply to customer
        session()->setFlashdata('success', 'Thank you for your message! We will contact you within 24
hours.');
        return redirect()->to('/contact');
    }
}
