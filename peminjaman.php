<?php
// 1. Jalankan session check untuk keamanan login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 2. Hubungkan dengan file koneksi
include 'koneksi.php';

// 3. LOGIKA SIMPAN (INSERT)
if (isset($_POST['simpan_peminjaman'])) {
    $kode_peminjaman    = mysqli_real_escape_string($koneksi, $_POST['kode_peminjaman']);
    $id_anggota         = $_POST['id_anggota'];
    $id_buku            = $_POST['id_buku'];
    $tanggal_pinjam     = $_POST['tanggal_pinjam'];
    $tanggal_jatuh_tempo= $_POST['tanggal_jatuh_tempo'];
    
    // PENGAMAN: Cek dulu apakah kode peminjaman sudah ada di database
    $cek_kode = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE kode_peminjaman='$kode_peminjaman'");
    if (mysqli_num_rows($cek_kode) > 0) {
        echo "<script>alert('Waduh, Kode Peminjaman $kode_peminjaman sudah terpakai! Silakan gunakan kode lain (misal: PMJ006).'); window.history.back();</script>";
        exit();
    }
    
    // Otomatis diset NULL karena ini hanya form pinjam
    $query_insert = "INSERT INTO peminjaman (kode_peminjaman, id_buku, id_anggota, tanggal_pinjam, tanggal_jatuh_tempo, tanggal_kembali) 
                     VALUES ('$kode_peminjaman', '$id_buku', '$id_anggota', '$tanggal_pinjam', '$tanggal_jatuh_tempo', NULL)";
    
    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>alert('Data transaksi berhasil disimpan!'); window.location='peminjaman.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}

// 4. LOGIKA EDIT & UPDATE 
$mode_edit = false;
$data_edit = null;

if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $cek_edit = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman='$id_edit'");
    if (mysqli_num_rows($cek_edit) > 0) {
        $data_edit = mysqli_fetch_assoc($cek_edit);
        $mode_edit = true;
    }
}

if (isset($_POST['update_peminjaman'])) {
    $id_peminjaman      = $_POST['id_peminjaman'];
    $kode_peminjaman    = mysqli_real_escape_string($koneksi, $_POST['kode_peminjaman']);
    $id_anggota         = $_POST['id_anggota'];
    $id_buku            = $_POST['id_buku'];
    $tanggal_pinjam     = $_POST['tanggal_pinjam'];
    $tanggal_jatuh_tempo= $_POST['tanggal_jatuh_tempo'];
    
    $query_update = "UPDATE peminjaman SET 
                        kode_peminjaman='$kode_peminjaman', 
                        id_buku='$id_buku', 
                        id_anggota='$id_anggota', 
                        tanggal_pinjam='$tanggal_pinjam', 
                        tanggal_jatuh_tempo='$tanggal_jatuh_tempo'
                     WHERE id_peminjaman='$id_peminjaman'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data transaksi berhasil diperbarui!'); window.location='peminjaman.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}

// 5. LOGIKA HAPUS DATA PEMINJAMAN
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $hapus = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_peminjaman = '$id_hapus'");

    if ($hapus) {
        echo "<script>alert('Riwayat peminjaman berhasil dihapus!'); window.location='peminjaman.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus riwayat.'); window.location='peminjaman.php';</script>";
    }
    exit();
}
?>
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
        <!-- HEADER TOPBAR -->
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
                        <a class="nav-link active" href="peminjaman.php">Peminjaman</a>
                        <a class="nav-link" href="pengembalian.php">Pengembalian</a>
                        <a class="nav-link" href="logout.php">Log-out</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">Sirkulasi Peminjaman Buku</h1>

            <div class="alert alert-warning">
                Pastikan status buku tersedia sebelum melakukan peminjaman baru.
            </div>

            <!-- FORM INPUT PEMINJAMAN -->
            <div class="card card-box mb-4">
                <div class="card-header bg-white">
                    <strong><?php echo $mode_edit ? 'Form Edit Transaksi' : 'Form Peminjaman Baru'; ?></strong>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php if ($mode_edit) { ?>
                            <input type="hidden" name="id_peminjaman" value="<?php echo $data_edit['id_peminjaman']; ?>">
                        <?php } ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Peminjaman <span class="required">*</span></label>
                                <input type="text" name="kode_peminjaman" class="form-control" required placeholder="PMJ001" 
                                       value="<?php echo $mode_edit ? $data_edit['kode_peminjaman'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Anggota <span class="required">*</span></label>
                                <select name="id_anggota" class="form-select" required>
                                    <option value="">Pilih anggota</option>
                                    <?php 
                                    $query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota");
                                    while($a = mysqli_fetch_array($query_anggota)) {
                                        $selected = ($mode_edit && $data_edit['id_anggota'] == $a['id_anggota']) ? 'selected' : '';
                                        echo "<option value='".$a['id_anggota']."' $selected>".$a['kode_anggota']." - ".$a['nama_anggota']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Buku <span class="required">*</span></label>
                                <select name="id_buku" class="form-select" required>
                                    <option value="">Pilih buku</option>
                                    <?php 
                                    $query_buku = mysqli_query($koneksi, "SELECT * FROM buku");
                                    while($b = mysqli_fetch_array($query_buku)) {
                                        $selected = ($mode_edit && $data_edit['id_buku'] == $b['id_buku']) ? 'selected' : '';
                                        echo "<option value='".$b['id_buku']."' $selected>".$b['kode_buku']." - ".$b['judul_buku']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pinjam <span class="required">*</span></label>
                                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" class="form-control" required 
                                       value="<?php echo $mode_edit ? $data_edit['tanggal_pinjam'] : date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Jatuh Tempo (Otomatis 10 Hari) <span class="required">*</span></label>
                                <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" class="form-control" required 
                                       value="<?php echo $mode_edit ? $data_edit['tanggal_jatuh_tempo'] : date('Y-m-d', strtotime('+10 days')); ?>" readonly>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <?php if ($mode_edit) { ?>
                                    <button type="submit" name="update_peminjaman" class="btn btn-primary">Update Transaksi</button>
                                    <a href="peminjaman.php" class="btn btn-outline-secondary">Batal</a>
                                <?php } else { ?>
                                    <button type="submit" name="simpan_peminjaman" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $query_tampil = "SELECT peminjaman.*, buku.judul_buku AS judul_buku, anggota.nama_anggota AS nama_anggota 
                                             FROM peminjaman 
                                             INNER JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                             INNER JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota 
                                             ORDER BY id_peminjaman DESC";
                            
                            $data_peminjaman = mysqli_query($koneksi, $query_tampil);
                            
                            while($row = mysqli_fetch_array($data_peminjaman)) {
                                $tgl_jatuh_tempo = strtotime($row['tanggal_jatuh_tempo']);
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['kode_peminjaman']; ?></td>
                                    <td><?php echo $row['judul_buku']; ?></td>
                                    <td><?php echo $row['nama_anggota']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['tanggal_jatuh_tempo'])); ?></td>
                                    <td>
                                        <?php 
                                        if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') {
                                            if (strtotime(date('Y-m-d')) > $tgl_jatuh_tempo) {
                                                echo '<span class="badge text-bg-danger">Terlambat</span>';
                                            } else {
                                                echo '<span class="badge text-bg-warning">Dipinjam</span>';
                                            }
                                        } else {
                                            echo '<span class="badge text-bg-success">Dikembalikan</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <!-- Tombol Terima Buku sudah Dihapus, sisa Edit dan Hapus -->
                                        <div class="d-flex gap-1">
                                            <a href="peminjaman.php?edit=<?php echo $row['id_peminjaman']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="peminjaman.php?hapus=<?php echo $row['id_peminjaman']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus riwayat peminjaman ini?')">Hapus</a>
                                        </div>
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

<script>
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        let tglPinjam = new Date(this.value);
        if (!isNaN(tglPinjam.getTime())) {
            tglPinjam.setDate(tglPinjam.getDate() + 10);
            let day = tglPinjam.getDate().toString().padStart(2, '0');
            let month = (tglPinjam.getMonth() + 1).toString().padStart(2, '0');
            let year = tglPinjam.getFullYear();
            document.getElementById('tanggal_jatuh_tempo').value = year + '-' + month + '-' + day;
        }
    });
</script>
</body>
</html>