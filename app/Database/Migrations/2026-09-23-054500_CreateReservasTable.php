<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservasTable extends Migration
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
            'area_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'residente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'data_reserva' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'horario_inicio' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'horario_fim' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pendente',
                'null'       => false,
                'comment'    => 'pendente, confirmada, cancelada',
            ],
            'valor_taxa' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
                'null'       => false,
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('id', true);
        $this->forge->addKey(['area_id', 'data_reserva']);
        $this->forge->addKey('residente_id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('area_id', 'areas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('residente_id', 'residentes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('reservas', true, [
            'ENGINE' => 'InnoDB',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('reservas', true);
    }
}
