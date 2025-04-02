<?php

namespace App\Controllers;

use App\Models\AngsuranKavlingModel;
use App\Models\AngsuranRumahModel;
use App\Models\RenovasiModel;
use App\Models\HutangModel;

class Dashboard extends BaseController
{
    protected $kavlingModel;
    protected $rumahModel;
    protected $renovasiModel;
    protected $hutangModel;

    public function __construct()
    {
        $this->kavlingModel = new AngsuranKavlingModel();
        $this->rumahModel = new AngsuranRumahModel();
        $this->renovasiModel = new RenovasiModel();
        $this->hutangModel = new HutangModel();
    }

    public function index()
    {
        $data['total_kavling'] = $this->kavlingModel->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $data['total_rumah'] = $this->rumahModel->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $data['total_renovasi'] = $this->renovasiModel->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        
        $data['total_hutang'] = $this->hutangModel->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $data['total_angsuran'] = $data['total_kavling'] + $data['total_rumah'];
        $data['sisa_hutang'] = $data['total_hutang'] - $data['total_angsuran'];

        return view('dashboard', $data);
    }
} 