<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToAngsuranKavling extends Migration
{
    public function up()
    {
        // Add location column to existing table
        $this->forge->addColumn('angsuran_kavling', [
            'lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'after' => 'id',
                'null' => false,
            ]
        ]);

        // Add index for better query performance
        $this->forge->addKey('lokasi');
    }

    public function down()
    {
        // Remove the column if rolling back
        $this->forge->dropColumn('angsuran_kavling', 'lokasi');
    }
}
