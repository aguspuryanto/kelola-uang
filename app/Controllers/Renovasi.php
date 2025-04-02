<?php

namespace App\Controllers;

use App\Models\RenovasiModel;

class Renovasi extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new RenovasiModel();
    }

    public function index()
    {
        $data['renovasi'] = $this->model->findAll();
        return view('renovasi', $data);
    }

    public function create()
    {
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->model->insert($data);
        return redirect()->to('/renovasi')->with('success', 'Data berhasil ditambahkan');
    }

    public function update($id)
    {
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->model->update($id, $data);
        return redirect()->to('/renovasi')->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/renovasi')->with('success', 'Data berhasil dihapus');
    }
} 