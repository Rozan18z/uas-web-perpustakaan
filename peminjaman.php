<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
<header class="topbar">
    <div class="page-shell px-3 py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="brand-mark">P</span>
                <div>
                    <div class="brand-title">Sistem Perpustakaan</div>
                    <div class="brand-subtitle">Katalog, anggota, dan peminjaman</div>
                </div>
            </div>

            <nav class="nav nav-pills">
                <a class="nav-link" href="home.php">Dashboard</a>
                <a class="nav-link" href="buku.php">Buku</a>
                <a class="nav-link" href="anggota.php">Anggota</a>
                <a class="nav-link  active" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link" href="logout.php">Log-out</a>
            </nav>
        </div>
    </div>
</header>

        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">Input Peminjaman Buku</h1>

            <div class="alert alert-warning">
                Pastikan status buku tersedia sebelum melakukan peminjaman.
            </div>

            <div class="card card-box mb-4">
                <div class="card-header bg-white">
                    <strong>Form Peminjaman</strong>
                </div>
                <div class="card-body">
                    <form action="#" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Peminjaman <span class="required">*</span></label>
                                <input type="text" name="kode_peminjaman" class="form-control" required placeholder="PMJ001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Anggota <span class="required">*</span></label>
                                <select name="id_anggota" class="form-select" required>
                                    <option value="">Pilih anggota</option>
                                    <option value="1">AG001 - Siti Aminah</option>
                                    <option value="2">AG002 - Andi Pratama</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Buku <span class="required">*</span></label>
                                <select name="id_buku" class="form-select" required>
                                    <option value="">Pilih buku</option>
                                    <option value="1">BK001 - Dasar Pemrograman Web</option>
                                    <option value="2">BK002 - Manajemen Basis Data</option>
                                    <option value="3">BK003 - Algoritma dan Struktur Data</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pinjam <span class="required">*</span></label>
                                <input type="date" name="tanggal_pinjam" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Jatuh Tempo <span class="required">*</span></label>
                                <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Peminjaman</label>
                                <select name="status_peminjaman" class="form-select">
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="dikembalikan">Dikembalikan</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-box">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Buku</th>
                                <th>Anggota</th>
                                <th>Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>PMJ001</td>
                                <td>Algoritma dan Struktur Data</td>
                                <td>Siti Aminah</td>
                                <td>20 Juni 2026</td>
                                <td>27 Juni 2026</td>
                                <td>-</td>
                                <td><span class="badge text-bg-warning">Dipinjam</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="#" class="btn btn-sm btn-success">Kembali</a>
                                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>