<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => env('login.username'),
            'password' => password_hash(env('login.password'), PASSWORD_BCRYPT),
        ];

        $this->db->table('users')->insert($data);
    }
}
