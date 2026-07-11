<?php 
// Memanggil konfigurasi database
    include 'koneksi.php'; 
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Buku</title>
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
                <a class="nav-link active" href="buku.php">Buku</a>
                <a class="nav-link" href="anggota.php">Anggota</a>
                <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link" href="logout.php">Log-out</a>
            </nav>
        </div>
    </div>
</header>

        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">CRUD Data Buku</h1>

            <div class="alert alert-info">
                Data buku berhasil diproses.
            </div>

            <form action="#" method="get" class="row g-2 mb-3">
                <div class="col-md-9">
                    <input type="search" name="keyword" class="form-control" placeholder="Cari kode, judul, penulis, atau kategori buku">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Cari Buku</button>
                </div>
            </form>

            <div class="card card-box mb-4">
                <div class="card-header bg-white">
                    <strong>Form Tambah / Edit Buku</strong>
                </div>
                <div class="card-body">
                    <form action="#" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Buku <span class="required">*</span></label>
                                <input type="text" name="kode_buku" class="form-control" required placeholder="BK001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Judul Buku <span class="required">*</span></label>
                                <input type="text" name="judul_buku" class="form-control" required minlength="3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Penulis <span class="required">*</span></label>
                                <input type="text" name="penulis" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" min="1900" max="2099">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok <span class="required">*</span></label>
                                <input type="number" name="stok" class="form-control" required min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Buku</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
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
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mengambil data dari tabel buku di database
                            $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
                        
                            // Variabel untuk penomoran otomatis tabel
                            $no = 1;
                        
                            // Looping untuk menampilkan data secara dinamis
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data['kode_buku']; ?></td>
                                <td><?php echo $data['judul_buku']; ?></td>
                                <td><?php echo $data['penulis']; ?></td>
                                <td><?php echo $data['kategori']; ?></td>
                                <td><?php echo $data['stok']; ?></td>
                                <td>
                                    <!-- Menampilkan status dengan badge warna sesuai kondisi -->
                                    <?php if($data['status'] == 'tersedia') { ?>
                                        <span class="badge text-bg-success">Tersedia</span>
                                    <?php } else { ?>
                                        <span class="badge text-bg-warning"><?php echo ucfirst($data['status']); ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <!-- Tombol aksi yang membawa parameter ID Buku untuk Edit & Hapus -->
                                    <a href="buku_edit.php?id=<?php echo $data['id_buku']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="buku_hapus.php?id=<?php echo $data['id_buku']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                            <?php 
                            } // Penutup perulangan while
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>