<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApplicationTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'first_name'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'address'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'city'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'state'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'zip_code'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'password'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'user_type'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'customer'],
            'is_active'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'email_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'account_number'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'customer_name'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'address'         => ['type' => 'TEXT'],
            'phone'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'meter_number'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'connection_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'residential'],
            'status'          => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('account_number');
        $this->forge->addKey('customer_name');
        $this->forge->addKey('connection_type');
        $this->forge->addKey('status');
        $this->forge->createTable('customer_accounts', true);
    }

    public function down(): void
    {
        // These tables may have existed before the migration (e.g. SQL import).
        throw new \RuntimeException('Automatic rollback is disabled to preserve imported data. Restore a database backup instead.');
    }
}
