<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateAccountsToShield extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'role_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        if (! $this->db->tableExists('accounts')) {
            return;
        }

        foreach ($this->db->table('accounts')->get()->getResultArray() as $account) {
            $now = date('Y-m-d H:i:s');

            $this->db->table('users')->insert([
                'username'   => $account['username'],
                'active'     => 1,
                'role_id'    => $account['role_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $userId = (int) $this->db->insertID();

            $this->db->table('auth_identities')->insert([
                'user_id'     => $userId,
                'type'        => 'email_password',
                'secret'      => $account['username'],
                'secret2'     => $account['password'],
                'force_reset' => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $this->db->table('auth_groups_users')->insert([
                'user_id'    => $userId,
                'group'      => strtolower($account['role']),
                'created_at' => $now,
            ]);
        }

        $this->forge->dropTable('accounts');
    }

    public function down(): void
    {
        if (! $this->db->tableExists('accounts')) {
            $this->forge->addField([
                'account_id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                ],
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
            ]);
            $this->forge->addKey('account_id', true);
            $this->forge->addUniqueKey('username');
            $this->forge->createTable('accounts');
        }

        foreach ($this->db->table('users')
            ->select('users.id, users.username, users.role_id, auth_identities.secret2, auth_groups_users.group')
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->where('auth_identities.type', 'email_password')
            ->get()
            ->getResultArray() as $user) {
            $this->db->table('accounts')->insert([
                'account_id' => $user['id'],
                'username'   => $user['username'],
                'password'   => $user['secret2'],
                'role'       => ucfirst($user['group']),
                'role_id'    => $user['role_id'],
            ]);
        }

        $this->forge->dropColumn('users', 'role_id');
    }
}
