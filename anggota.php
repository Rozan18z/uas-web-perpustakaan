<?php
// 1. Hubungkan dengan database
include 'koneksi.php';

$status_edit = false;
$kode_anggota = ""; 
$nama_anggota = ""; 
$jenis_kelamin = ""; 
$no_hp        = ""; 
$alamat       = "";

// 2. LOGIKA EDIT (Mengambil data lama)
if (isset($_GET['edit'])) {
    $status_edit = true;
    $id_anggota = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $ambil_data = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id_anggota = '$id_anggota'");
    if (mysqli_num_rows($ambil_data) > 0) {
        $data_lama = mysqli_fetch_array($ambil_data);
        $kode_anggota  = $data_lama['kode_anggota'];
        $nama_anggota  = $data_lama['nama_anggota'];
        $jenis_kelamin = $data_lama['jenis_kelamin'];
        $no_hp         = $data_lama['no_hp']; // Sesuai kolom database: no_hp
        $alamat        = $data_lama['alamat'];
    }
}

/// 3. LOGIKA TOMBOL HAPUS (Sudah dipasang proteksi relasi transaksi)
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    // 1. Cek dulu apakah ID anggota ini ada di tabel peminjaman
    $cek_transaksi = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_anggota = '$id_hapus'");
    
    if (mysqli_num_rows($cek_transaksi) > 0) {
        // Jika ada transaksi, batalkan hapus dan beri peringatan rapi
        echo "<script>alert('Waduh, data anggota ini tidak bisa dihapus karena memiliki riwayat transaksi di Peminjaman!'); window.location='anggota.php';</script>";
        exit();
    } else {
        // Jika bersih dari transaksi, baru eksekusi perintah hapus
        $eksekusi_hapus = mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota = '$id_hapus'");
        if ($eksekusi_hapus) {
            echo "<script>alert('Selamat! Data anggota berhasil dihapus.'); window.location='anggota.php';</script>";
        } else {
            echo "<script>alert('Waduh, gagal menghapus data dari database.'); window.location='anggota.php';</script>";
        }
    }
}

// 4. LOGIKA SIMPAN & UPDATE
if (isset($_POST['simpan_anggota'])) {
    $kode_anggota  = mysqli_real_escape_string($koneksi, $_POST['kode_anggota']);
    $nama_anggota  = mysqli_real_escape_string($koneksi, $_POST['nama_anggota']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    if ($status_edit) {
        $id_anggota = mysqli_real_escape_string($koneksi, $_GET['edit']);
        
        // Cek apakah kode_anggota yang baru diinput sudah dipakai oleh orang lain
        $cek_kode = mysqli_query($koneksi, "SELECT * FROM anggota WHERE kode_anggota='$kode_anggota' AND id_anggota != '$id_anggota'");
        if (mysqli_num_rows($cek_kode) > 0) {
            echo "<script>alert('Waduh, Kode Anggota tersebut sudah digunakan oleh anggota lain!'); window.history.back();</script>";
            exit();
        }
        
        $query = "UPDATE anggota SET kode_anggota='$kode_anggota', nama_anggota='$nama_anggota', jenis_kelamin='$jenis_kelamin', no_hp='$no_hp', alamat='$alamat' WHERE id_anggota='$id_anggota'";
    } else {
        // Cek apakah kode_anggota yang mau dimasukkan sudah ada di database
        $cek_kode = mysqli_query($koneksi, "SELECT * FROM anggota WHERE kode_anggota='$kode_anggota'");
        if (mysqli_num_rows($cek_kode) > 0) {
            echo "<script>alert('Waduh, Kode Anggota tersebut sudah terdaftar! Gunakan kode lain (misal: AG003).'); window.history.back();</script>";
            exit();
        }
        
        $query = "INSERT INTO anggota (kode_anggota, nama_anggota, jenis_kelamin, no_hp, alamat, status) VALUES ('$kode_anggota', '$nama_anggota', '$jenis_kelamin', '$no_hp', '$alamat', 'aktif')";
    }
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Selamat! Data anggota berhasil diproses.'); window.location='anggota.php';</script>";
    } else {
        echo "<script>alert('Waduh, gagal menyimpan data ke database.');</script>";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- TOPBAR NAVBAR (Sama persis susunannya dengan buku.php) -->
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
                        <a class="nav-link active" href="anggota.php">Anggota</a>
                        <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                        <a class="nav-link" href="logout.php">Log-out</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- MAIN LAYOUT CONTENT -->
        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">CRUD Data Anggota</h1>

            <div class="alert alert-info">
                Data anggota berhasil diproses.
            </div>

            <!-- FORM INPUT DATA ANGGOTA -->
            <div class="card card-box mb-4">
                <div class="card-header bg-white">
                    <strong>Form Tambah / Edit Anggota</strong>
                </div>
                <div class="card-body">
                   <form action="" method="post">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Kode Anggota <span class="required">*</span></label>
            <!-- Tambahkan value PHP di bawah ini -->
            <input type="text" name="kode_anggota" class="form-control" required placeholder="Contoh: AG001" value="<?php echo htmlspecialchars($kode_anggota); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Anggota <span class="required">*</span></label>
            <!-- Tambahkan value PHP di bawah ini -->
            <input type="text" name="nama_anggota" class="form-control" required value="<?php echo htmlspecialchars($nama_anggota); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <!-- Menambahkan kondisi selected otomatis sesuai data di database -->
                <option value="L" <?php echo ($jenis_kelamin == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="P" <?php echo ($jenis_kelamin == 'P') ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">No. Telepon</label>
            <!-- Tambahkan value PHP di bawah ini -->
            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="<?php echo htmlspecialchars($no_hp); ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Alamat</label>
            <!-- Taruh variabel PHP di dalam textarea -->
            <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($alamat); ?></textarea>
        </div>
        <div class="col-12">
            <!-- Nama tombol tetap 'simpan_anggota' agar ditangkap oleh logika PHP di atas -->
            <button type="submit" name="simpan_anggota" class="btn btn-primary">
                <?php echo $status_edit ? 'Simpan Perubahan' : 'Simpan'; ?>
            </button>
            <a href="anggota.php" class="btn btn-outline-secondary">Reset / Batal</a>
        </div>
    </div>
</form>
                </div>
            </div>

            <!-- TABEL DATA ANGGOTA -->
            <div class="card card-box">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Anggota</th>
                                <th>Jenis Kelamin</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id_anggota DESC");
                            $no = 1;
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data['kode_anggota']; ?></td>
                                <td><?php echo $data['nama_anggota']; ?></td>
                                <td><?php echo ($data['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
                                <td><?php echo $data['no_hp']; ?></td>
                                <td><?php echo $data['alamat']; ?></td>
                                <td>
                                    <?php if($data['status'] == 'aktif') { ?>
                                        <span class="badge text-bg-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge text-bg-warning">Nonaktif</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="anggota.php?edit=<?php echo $data['id_anggota']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="anggota.php?hapus=<?php echo $data['id_anggota']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data anggota ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php 
                            } 
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