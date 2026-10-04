<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function scv(): string
    {
        return view('SCV', [
            'title'   => 'Setup Checklist Verification',
            'message' => 'Welcome to the SCV page.',
        ]);
    }
}
