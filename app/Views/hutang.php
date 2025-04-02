<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Hutang</h1>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Hutang Belum Lunas</h6>
                        </div>
                        <div>
                            <h4 class="mb-0">Rp <?= number_format($total_belum_lunas, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Hutang Lunas</h6>
                        </div>
                        <div>
                            <h4 class="mb-0">Rp <?= number_format($total_lunas, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-table me-1"></i>
                    <span class="me-3">Data Hutang</span>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                        <option value="/hutang" <?= $selected_status === null ? 'selected' : '' ?>>Semua Status</option>
                        <option value="/hutang?status=belum_lunas" <?= $selected_status === 'belum_lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                        <option value="/hutang?status=lunas" <?= $selected_status === 'lunas' ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pemberi</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Pelunasan</th>
                        <th>Keterangan</th>
                        <th style="width: 19%;text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($hutang as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= esc($row['nama_pemberi']) ?></td>
                            <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($row['status'] === 'belum_lunas'): ?>
                                    <span class="badge bg-danger">Belum Lunas</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['tanggal_pelunasan'] ? date('d/m/Y', strtotime($row['tanggal_pelunasan'])) : '-' ?></td>
                            <td><?= esc($row['keterangan']) ?></td>
                            <td class="text-center">
                                <?php if ($row['status'] === 'belum_lunas'): ?>
                                    <a href="/hutang/lunasi/<?= $row['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin melunasi hutang ini?')">
                                        <i class="fas fa-check"></i> Lunasi
                                    </a>
                                <?php else: ?>
                                    <a href="/hutang/batalkan-pelunasan/<?= $row['id'] ?>" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan pelunasan hutang ini?')">
                                        <i class="fas fa-undo"></i> Batalkan
                                    </a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Hutang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="/hutang/update/<?= $row['id'] ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" name="tanggal" value="<?= $row['tanggal'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama_pemberi" class="form-label">Nama Pemberi</label>
                                                <input type="text" class="form-control" name="nama_pemberi" value="<?= $row['nama_pemberi'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah" value="<?= $row['jumlah'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea class="form-control" name="keterangan"><?= $row['keterangan'] ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tanggal_pelunasan" class="form-label">Tanggal Pelunasan</label>
                                                <input type="date" class="form-control" name="tanggal_pelunasan" value="<?= $row['tanggal_pelunasan'] ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data hutang ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <a href="/hutang/delete/<?= $row['id'] ?>" class="btn btn-danger">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Hutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/hutang/create" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pemberi" class="form-label">Nama Pemberi</label>
                        <input type="text" class="form-control" name="nama_pemberi" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pelunasan" class="form-label">Tanggal Pelunasan</label>
                        <input type="date" class="form-control" name="tanggal_pelunasan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 