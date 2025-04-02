<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Ringkasan Keuangan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Total Hutang -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Hutang</h5>
                                <h3 class="card-text">Rp <?= number_format($total_hutang, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Angsuran -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Angsuran</h5>
                                <h3 class="card-text">Rp <?= number_format($total_angsuran, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Sisa Hutang -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Sisa Hutang</h5>
                                <h3 class="card-text">Rp <?= number_format($sisa_hutang, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Renovasi -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Renovasi</h5>
                                <h3 class="card-text">Rp <?= number_format($total_renovasi, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Angsuran -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail Angsuran</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Jenis Angsuran</th>
                                                <th>Total</th>
                                                <th>Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Angsuran Kavling</td>
                                                <td>Rp <?= number_format($total_kavling, 0, ',', '.') ?></td>
                                                <td><?= number_format(($total_kavling / $total_hutang) * 100, 2) ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Angsuran Rumah</td>
                                                <td>Rp <?= number_format($total_rumah, 0, ',', '.') ?></td>
                                                <td><?= number_format(($total_rumah / $total_hutang) * 100, 2) ?>%</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th>Total</th>
                                                <th>Rp <?= number_format($total_angsuran, 0, ',', '.') ?></th>
                                                <th><?= number_format(($total_angsuran / $total_hutang) * 100, 2) ?>%</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 