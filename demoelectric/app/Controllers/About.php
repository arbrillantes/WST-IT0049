<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'About Us - Puihaha Electric',
            'page' => 'about'
        ];
        return view('about', $data);
    }
}
