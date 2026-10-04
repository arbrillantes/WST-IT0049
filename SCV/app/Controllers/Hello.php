<?php

namespace App\Controllers;

class Hello extends BaseController
{
    public function index(): string
    {
        $data = [
            'title'   => 'Hello from CodeIgniter',
            'message' => 'Your route, controller, and view are connected successfully.',
        ];

        return view('hello', $data);
    }
}
