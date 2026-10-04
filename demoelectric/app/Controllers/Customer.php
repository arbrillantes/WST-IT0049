<?php

namespace App\Controllers;

use App\Models\CustomerAccount;

class Customer extends BaseController
{
    public function index(): string
    {
        return view('customer_dashboard', [
            'title' => 'My Account - Puihaha Electric',
            'page' => 'customer',
            // Identity comes from the authenticated session, never a URL/form ID.
            'customer' => (new CustomerAccount())->select('account_number,customer_name,first_name,last_name,email,phone,address,city,state,zip_code')
                ->find((int) session()->get('userId')),
        ]);
    }
}
