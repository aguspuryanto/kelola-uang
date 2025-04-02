<?php

namespace App\Models;

use CodeIgniter\Model;

class RenovasiModel extends Model
{
    protected $table = 'renovasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal', 'jumlah', 'keterangan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
} 