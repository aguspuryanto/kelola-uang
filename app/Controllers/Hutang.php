<?php

namespace App\Controllers;

use App\Models\HutangModel;

class Hutang extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new HutangModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        $data = [
            'hutang' => $this->model->getHutangByStatus($status),
            'total_belum_lunas' => $this->model->getTotalHutangBelumLunas(),
            'total_lunas' => $this->model->getTotalHutangLunas(),
            'selected_status' => $status
        ];
        
        return view('hutang', $data);
    }

    public function create()
    {
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'nama_pemberi' => $this->request->getPost('nama_pemberi'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status' => 'belum_lunas'
        ];

        $this->model->insert($data);
        return redirect()->to('/hutang')->with('success', 'Data hutang berhasil ditambahkan');
    }

    public function update($id)
    {
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'nama_pemberi' => $this->request->getPost('nama_pemberi'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if($this->request->getPost('tanggal_pelunasan')){
            $data['tanggal_pelunasan'] = $this->request->getPost('tanggal_pelunasan');
        }

        $this->model->update($id, $data);
        return redirect()->to('/hutang')->with('success', 'Data hutang berhasil diupdate');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/hutang')->with('success', 'Data hutang berhasil dihapus');
    }

    public function lunasi($id)
    {
        $this->model->lunasi($id);
        return redirect()->to('/hutang')->with('success', 'Hutang berhasil dilunasi');
    }

    public function batalkanPelunasan($id)
    {
        $this->model->batalkanPelunasan($id);
        return redirect()->to('/hutang')->with('success', 'Pelunasan hutang dibatalkan');
    }
} 