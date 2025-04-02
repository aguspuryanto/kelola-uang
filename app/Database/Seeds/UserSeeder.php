<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin@gmail.com',
            'password' => password_hash('admin1234', PASSWORD_BCRYPT),
        ];

        $this->db->table('users')->insert($data);
    }
}
