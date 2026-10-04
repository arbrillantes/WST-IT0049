<?php

namespace App\Database\Migrations;

use App\Models\CustomerAccount;
use CodeIgniter\Database\Migration;
use RuntimeException;

class MoveCustomerLogins extends Migration
{
    public function up(): void
    {
        // Do not merge people based only on an unverified email address.
        $duplicates = $this->db->query("SELECT LOWER(TRIM(email)) FROM customer_accounts WHERE email IS NOT NULL AND TRIM(email) <> '' GROUP BY LOWER(TRIM(email)) HAVING COUNT(*) > 1")->getResultArray();
        $conflicts = $this->db->query("SELECT u.id FROM users u JOIN customer_accounts c ON LOWER(TRIM(c.email)) = LOWER(TRIM(u.email)) WHERE u.user_type = 'customer'")->getResultArray();
        if ($duplicates !== [] || $conflicts !== []) {
            throw new RuntimeException('Customer email conflicts require review before moving logins; no identities were merged.');
        }

        $fields = [
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'state' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'zip_code' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'email_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'legacy_user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ];
        foreach ($fields as $name => $definition) {
            if (! $this->db->fieldExists($name, 'customer_accounts')) {
                $this->forge->addColumn('customer_accounts', [$name => $definition]);
            }
        }
        $this->forge->modifyColumn('customer_accounts', [
            'customer_name' => ['type' => 'VARCHAR', 'constraint' => 201],
        ]);
        // Optional contact emails use NULL so many non-login records can omit one.
        $this->db->query("UPDATE customer_accounts SET email = NULL WHERE TRIM(email) = ''");
        $this->db->query('UPDATE customer_accounts SET email = LOWER(TRIM(email)) WHERE email IS NOT NULL');
        $indexes = $this->db->getIndexData('customer_accounts');
        foreach (['email' => 'customer_email_unique', 'legacy_user_id' => 'customer_legacy_user_unique'] as $field => $name) {
            if (! isset($indexes[$name])) {
                $this->forge->addUniqueKey($field, $name);
                $this->forge->processIndexes('customer_accounts');
            }
        }

        $this->db->transBegin();
        try {
            $customers = $this->db->table('users')->where('user_type', 'customer')->get()->getResultArray();
            foreach ($customers as $customer) {
                $row = array_intersect_key($customer, array_flip([
                    'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip_code',
                    'password', 'is_active', 'email_verified', 'created_at', 'updated_at',
                ]));
                $row['email'] = strtolower(trim($customer['email']));
                $row['account_number'] = CustomerAccount::generateAccountNumber();
                $row['customer_name'] = trim($customer['first_name'] . ' ' . $customer['last_name']);
                $row['connection_type'] = 'residential';
                $row['status'] = 'active';
                $row['legacy_user_id'] = $customer['id'];

                // Query builder bypasses model hashing: preserve the existing hash exactly.
                if (! $this->db->table('customer_accounts')->insert($row)) {
                    throw new RuntimeException('Customer migration insert failed.');
                }
                $copy = $this->db->table('customer_accounts')->where('legacy_user_id', $customer['id'])->get()->getRowArray();
                foreach ($row as $field => $value) {
                    if ((string) $copy[$field] !== (string) $value) {
                        throw new RuntimeException('Customer migration verification failed.');
                    }
                }
                $this->db->table('users')->where('id', $customer['id'])->where('user_type', 'customer')->delete();
                if ($this->db->affectedRows() !== 1) {
                    throw new RuntimeException('Original customer identity could not be relocated.');
                }
            }
            if (! $this->db->transStatus()) {
                throw new RuntimeException('Customer migration transaction failed.');
            }
            $this->db->transCommit();
        } catch (\Throwable $exception) {
            $this->db->transRollback();
            throw $exception;
        }

        $this->forge->modifyColumn('users', [
            'user_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'admin'],
        ]);
    }

    public function down(): void
    {
        throw new RuntimeException('Restore a pre-migration backup to undo this identity-table move without losing data.');
    }
}
