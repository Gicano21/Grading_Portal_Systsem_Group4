<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AccountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'account_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('account_id', true);
        $this->forge->addUniqueKey('username');

        $this->forge->createTable('accounts');
    }

    public function down()
    {
        $this->forge->dropTable('accounts');
    }
}
