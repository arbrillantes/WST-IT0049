<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Puihaha Electric - Reliable Energy Solutions',
            'page' => 'home'
        ];
        return view('home', $data);
    }
}
