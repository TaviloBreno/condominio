<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResidentFieldsToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'residente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'username',
            ],
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'residente',
                'null'       => true,
                'after'      => 'residente_id',
            ],
            'primeiro_acesso' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
                'after'      => 'tipo',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['residente_id', 'tipo', 'primeiro_acesso']);
    }
}
