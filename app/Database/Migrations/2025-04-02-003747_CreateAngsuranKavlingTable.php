<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAngsuranKavlingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jumlah' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'keterangan' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('angsuran_kavling');
    }

    public function down()
    {
        $this->forge->dropTable('angsuran_kavling');
    }
}
