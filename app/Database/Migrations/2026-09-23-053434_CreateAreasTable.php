<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAreasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'capacidade' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 10,
                'null'       => false,
            ],
            'horario_inicio' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'horario_fim' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'taxa_reserva' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
                'null'       => false,
            ],
            'ativo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('nome');
        $this->forge->addKey('ativo');
        $this->forge->createTable('areas', true);
    }

    public function down()
    {
        $this->forge->dropTable('areas', true);
    }
}
