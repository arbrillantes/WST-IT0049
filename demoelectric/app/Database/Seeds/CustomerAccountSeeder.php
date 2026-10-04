<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

class CustomerAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Safe to run again: never overwrite existing customer records.
        if ($this->db->table('customer_accounts')->countAllResults() > 0) {
            return;
        }

        $sql = file_get_contents(APPPATH . 'Database/Fixtures/customer_accounts.sql');
        if ($sql === false || ! preg_match('/INSERT INTO `customer_accounts`.*?;/s', $sql, $match)) {
            throw new RuntimeException('Customer account sample INSERT was not found.');
        }

        // Use the exact 25 records from the supplied professor SQL export.
        $this->db->query($match[0]);
    }
}
