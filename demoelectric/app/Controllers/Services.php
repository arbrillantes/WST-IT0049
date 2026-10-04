<?php

namespace App\Controllers;

class Services extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Our Services - Puihaha Electric',
            'page' => 'services'
        ];
        return view('services', $data);
    }
}
