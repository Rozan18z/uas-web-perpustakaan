<?php 
// Memanggil konfigurasi database
    include 'koneksi.php'; 

// Cek apakah tombol Simpan dengan name="simpan_buku" sudah diklik
if (isset($_POST['simpan_buku'])) {
    
    // Mengambil data yang dikirimkan oleh form inputan HTML
    $kode_buku    = $_POST['kode_buku'];
    $judul_buku   = $_POST['judul_buku'];
    $penulis      = $_POST['penulis'];
    $penerbit     = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori     = $_POST['kategori'];
    $stok         = $_POST['stok'];
    $status       = $_POST['status'];

    // Menjalankan perintah SQL INSERT INTO untuk menyimpan data ke database
    $insert = mysqli_query($koneksi, "INSERT INTO buku (kode_buku, judul_buku, penulis, penerbit, tahun_terbit, kategori, stok, status) 
              VALUES ('$kode_buku', '$judul_buku', '$penulis', '$penerbit', '$tahun_terbit', '$kategori', '$stok', '$status')");

    // Cek apakah proses query ke database berhasil atau gagal
    if ($insert) {
        // Jika berhasil, munculkan notifikasi sukses dan refresh kembali ke halaman buku.php
        echo "<script>alert('Selamat! Data buku baru berhasil disimpan.'); window.location='buku.php';</script>";
    } else {
        // Jika gagal (misal kode buku kembar/duplicate), munculkan notifikasi gagal
        echo "<script>alert('Waduh, gagal menyimpan data ke database.');</script>";
    }
    }

    // FITUR EDIT (UPDATE)
    if (isset($_POST['update_buku'])) {
        $id_buku      = $_POST['id_buku'];
        $kode_buku    = $_POST['kode_buku'];
        $judul_buku   = $_POST['judul_buku'];
        $penulis      = $_POST['penulis'];
        $penerbit     = $_POST['penerbit'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $kategori     = $_POST['kategori'];
        $stok         = $_POST['stok'];
        $status       = $_POST['status'];
    
        $update = mysqli_query($koneksi, "UPDATE buku SET 
                    kode_buku='$kode_buku', 
                    judul_buku='$judul_buku', 
                    penulis='$penulis', 
                    penerbit='$penerbit', 
                    tahun_terbit='$tahun_terbit', 
                    kategori='$kategori', 
                    stok='$stok', 
                    status='$status' 
                  WHERE id_buku='$id_buku'");
    
        if ($update) {
            echo "<script>alert('Data buku berhasil diperbarui.'); window.location='buku.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data buku.');</script>";
        }
    }
    
    // FITUR HAPUS (DELETE)
    if (isset($_GET['hapus'])) {
        $id_hapus = $_GET['hapus'];
        $hapus = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id_hapus'");
    
        if ($hapus) {
            echo "<script>alert('Data buku berhasil dihapus.'); window.location='buku.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data buku.');</script>";
        }
        exit();
    }

    // AMBIL DATA UNTUK MODE EDIT
    $mode_edit = false;
    $data_edit = null;
    
    if (isset($_GET['edit'])) {
        $id_edit  = $_GET['edit'];
        $cek_edit = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id_edit'");
        if (mysqli_num_rows($cek_edit) > 0) {
            $data_edit = mysqli_fetch_assoc($cek_edit);
            $mode_edit = true;
        }
    }
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

            <form action="buku.php" method="get" class="row g-2 mb-3">
                <div class="col-md-9">
                    <input type="search" name="keyword" class="form-control" placeholder="Cari kode, judul, penulis, atau kategori buku">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Cari Buku</button>
                </div>
            </form>

            <div class="card card-box mb-4">
                <div class="card-header bg-white">
                    <strong><?php echo $mode_edit ? 'Form Edit Buku' : 'Form Tambah Buku'; ?></strong>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php if ($mode_edit) { ?>
                            <input type="hidden" name="id_buku" value="<?php echo $data_edit['id_buku']; ?>">
                        <?php } ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Buku <span class="required">*</span></label>
                                <input type="text" name="kode_buku" class="form-control" required placeholder="BK001"
                                       value="<?php echo $mode_edit ? $data_edit['kode_buku'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Judul Buku <span class="required">*</span></label>
                                <input type="text" name="judul_buku" class="form-control" required minlength="3"
                                       value="<?php echo $mode_edit ? $data_edit['judul_buku'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Penulis <span class="required">*</span></label>
                                <input type="text" name="penulis" class="form-control" required
                                       value="<?php echo $mode_edit ? $data_edit['penulis'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control"
                                       value="<?php echo $mode_edit ? $data_edit['penerbit'] : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" min="1900" max="2099"
                                       value="<?php echo $mode_edit ? $data_edit['tahun_terbit'] : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control"
                                       value="<?php echo $mode_edit ? $data_edit['kategori'] : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok <span class="required">*</span></label>
                                <input type="number" name="stok" class="form-control" required min="0"
                                       value="<?php echo $mode_edit ? $data_edit['stok'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Buku</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia" <?php echo ($mode_edit && $data_edit['status']=='tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                    <option value="dipinjam" <?php echo ($mode_edit && $data_edit['status']=='dipinjam') ? 'selected' : ''; ?>>Dipinjam</option>
                                    <option value="rusak" <?php echo ($mode_edit && $data_edit['status']=='rusak') ? 'selected' : ''; ?>>Rusak</option>
                                    <option value="hilang" <?php echo ($mode_edit && $data_edit['status']=='hilang') ? 'selected' : ''; ?>>Hilang</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <?php if ($mode_edit) { ?>
                                    <button type="submit" name="update_buku" class="btn btn-primary">Update</button>
                                    <a href="buku.php" class="btn btn-outline-secondary">Batal</a>
                                <?php } else { ?>
                                    <button type="submit" name="simpan_buku" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <?php } ?>
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
                            // LOGIKA PENCARIAN BARU
                            if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                                $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                                // Mencari berdasarkan kode, judul, penulis, atau kategori
                                $query = mysqli_query($koneksi, "SELECT * FROM buku WHERE 
                                          kode_buku LIKE '%$keyword%' OR 
                                          judul_buku LIKE '%$keyword%' OR 
                                          penulis LIKE '%$keyword%' OR 
                                          kategori LIKE '%$keyword%' 
                                          ORDER BY id_buku DESC");
                            } else {
                                // Jika tidak ada pencarian, tampilkan semua data bawaan kamu
                                $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
                            }
                            
                            // Variabel untuk penomoran otomatis tabel
                            $no = 1;
                            
                            // Looping untuk menampilkan data secara dinamis
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
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
                                    <a href="buku.php?edit=<?php echo $data['id_buku']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="buku.php?hapus=<?php echo $data['id_buku']; ?>" class="btn btn-sm btn-danger">Hapus</a>
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