<?php

namespace App\Database\Seeds;

use App\Models\User;
use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $model = new User();
        $existing = $model->where('username', 'admin')->first();
        if ($existing !== null) {
            if ($existing['user_type'] !== 'admin') {
                throw new \RuntimeException('Username admin is already assigned to a non-admin user.');
            }
            return; // Never reset an existing admin password when reseeding.
        }

        $id = $model->insert([
            'username' => 'admin',
            'first_name' => 'Puihaha',
            'last_name' => 'Administrator',
            'email' => 'admin@puihaha.example',
            'password' => 'admin123', // Explicitly requested local classroom account.
            'phone' => null,
            'address' => '', 'city' => '', 'state' => '', 'zip_code' => '',
            'user_type' => 'admin', 'is_active' => true, 'email_verified' => false,
        ]);
        if (! $id) {
            throw new \RuntimeException('Admin creation failed: ' . implode(' ', $model->errors()));
        }
    }
}
