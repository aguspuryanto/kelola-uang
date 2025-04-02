<?php

namespace App\Models;

use CodeIgniter\Model;

class AngsuranKavlingModel extends Model
{
    protected $table = 'angsuran_kavling';
    protected $primaryKey = 'id';
    protected $allowedFields = ['lokasi', 'tanggal', 'jumlah', 'keterangan'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get unique locations
    public function getLocations()
    {
        return $this->select('lokasi')
                    ->distinct()
                    ->orderBy('lokasi', 'ASC')
                    ->findAll();
    }

    // Get records by location
    public function getByLocation($lokasi)
    {
        return $this->where('lokasi', $lokasi)
                    ->orderBy('tanggal', 'DESC')
                    ->findAll();
    }

    // Get summary by location
    public function getSummaryByLocation($lokasi)
    {
        return $this->selectSum('jumlah')
                    ->where('lokasi', $lokasi)
                    ->get()
                    ->getRow()
                    ->jumlah ?? 0;
    }
} 