<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargabeliToHutang extends Migration
{
    public function up()
    {
        // Add location column to existing table
        $this->forge->addColumn('hutang', [
            'harga_beli' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'after' => 'nama_pemberi',
                'null' => false,
            ]
        ]);

        // Add index for better query performance
        $this->forge->addKey('harga_beli');
    }

    public function down()
    {
        $this->forge->dropColumn('hutang', 'harga_beli');
    }
}
