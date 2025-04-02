<?php

namespace App\Models;

use CodeIgniter\Model;

class HutangModel extends Model
{
    protected $table = 'hutang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal', 'nama_pemberi', 'jumlah', 'status', 'tanggal_pelunasan', 'keterangan'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get total hutang yang belum lunas
    public function getTotalHutangBelumLunas()
    {
        return $this->selectSum('jumlah')
                    ->where('status', 'belum_lunas')
                    ->get()
                    ->getRow()
                    ->jumlah ?? 0;
    }

    // Get total hutang yang sudah lunas
    public function getTotalHutangLunas()
    {
        return $this->selectSum('jumlah')
                    ->where('status', 'lunas')
                    ->get()
                    ->getRow()
                    ->jumlah ?? 0;
    }

    // Get hutang by status
    public function getHutangByStatus($status = null)
    {
        if ($status === null) {
            return $this->orderBy('tanggal', 'DESC')->findAll();
        }
        return $this->where('status', $status)
                    ->orderBy('tanggal', 'DESC')
                    ->findAll();
    }

    // Update status hutang menjadi lunas
    public function lunasi($id)
    {
        return $this->update($id, [
            'status' => 'lunas',
            'tanggal_pelunasan' => date('Y-m-d')
        ]);
    }

    // Batalkan pelunasan hutang
    public function batalkanPelunasan($id)
    {
        return $this->update($id, [
            'status' => 'belum_lunas',
            'tanggal_pelunasan' => null
        ]);
    }
} 