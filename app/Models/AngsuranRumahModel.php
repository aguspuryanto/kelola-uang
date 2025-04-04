<?php

namespace App\Models;

use CodeIgniter\Model;

class AngsuranRumahModel extends Model
{
    protected $table = 'angsuran_rumah';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal', 'jumlah', 'keterangan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getTotalAngsuran()
    {
        // where keterangan like '%angsuran%'
        return $this->db->table('angsuran_rumah')
            ->selectSum('jumlah')
            ->like('keterangan', 'angsuran') // Partial match condition
            ->get()->getRowArray();
    }

    public function getTotalHutang()
    {
        return $this->db->table('hutang')
            ->selectSum('jumlah')
            ->where('id', 1)
            ->get()->getRowArray();
    }
    

} 