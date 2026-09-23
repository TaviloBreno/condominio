<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCobrancasTable extends Migration
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
            'reserva_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'residente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'valor' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
                'null'       => false,
            ],
            'data_vencimento' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'data_pagamento' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pendente',
                'null'       => false,
                'comment'    => 'pendente, pago, cancelado, atrasado',
            ],
            'codigo_barras' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'forma_pagamento' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
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
        $this->forge->addKey('reserva_id');
        $this->forge->addKey('residente_id');
        $this->forge->addKey('status');
        $this->forge->addKey('data_vencimento');

        $this->forge->addForeignKey('reserva_id', 'reservas', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('residente_id', 'residentes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('cobrancas', true, [
            'ENGINE' => 'InnoDB',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('cobrancas', true);
    }
}
