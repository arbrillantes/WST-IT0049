<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminUsername extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addUniqueKey('username', 'users_username_unique');
        $this->forge->processIndexes('users');
    }

    public function down(): void
    {
        throw new \RuntimeException('Automatic rollback is disabled to preserve admin login identities.');
    }
}
