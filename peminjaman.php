<?php
// 1. Jalankan session check untuk keamanan login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 2. Hubungkan dengan file koneksi kamu
include 'koneksi.php';

// 3. Logika untuk menyimpan data transaksi ke database saat tombol Simpan diklik
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_peminjaman    = $_POST['kode_peminjaman'];
    $id_anggota        = $_POST['id_anggota'];
    $id_buku           = $_POST['id_buku'];
    $tanggal_pinjam     = $_POST['tanggal_pinjam'];
    $tanggal_jatuh_tempo= $_POST['tanggal_jatuh_tempo'];
    
    // Tanggal kembali diisi sesuai input, jika kosong maka set jadi NULL di database
    $tanggal_kembali    = !empty($_POST['tanggal_kembali']) ? "'".$_POST['tanggal_kembali']."'" : "NULL";

    // Query insert disesuaikan dengan struktur tabel peminjaman kamu
    $query_insert = "INSERT INTO peminjaman (kode_peminjaman, id_buku, id_anggota, tanggal_pinjam, tanggal_jatuh_tempo, tanggal_kembali) 
                     VALUES ('$kode_peminjaman', '$id_buku', '$id_anggota', '$tanggal_pinjam', '$tanggal_jatuh_tempo', $tanggal_kembali)";
    
    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>alert('Data transaksi berhasil disimpan!'); window.location='peminjaman.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }
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
                    <!-- Action dikosongkan agar memproses ke PHP di atas -->
                    <form action="" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Peminjaman <span class="required">*</span></label>
                                <input type="text" name="kode_peminjaman" class="form-control" required placeholder="PMJ001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Anggota <span class="required">*</span></label>
                                <select name="id_anggota" class="form-select" required>
                                    <option value="">Pilih anggota</option>
                                    <?php 
                                    // Mengambil data anggota langsung dari database temanmu secara dinamis
                                    $query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota");
                                    while($a = mysqli_fetch_array($query_anggota)) {
                                        // Sesuaikan nama kolom tabel anggota jika berbeda (misal nama_anggota)
                                        echo "<option value='".$a['id_anggota']."'>".$a['id_anggota']." - ".$a['nama']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Buku <span class="required">*</span></label>
                                <select name="id_buku" class="form-select" required>
                                    <option value="">Pilih buku</option>
                                    <?php 
                                    // Mengambil data buku langsung dari database temanmu secara dinamis
                                    $query_buku = mysqli_query($koneksi, "SELECT * FROM buku");
                                    while($b = mysqli_fetch_array($query_buku)) {
                                        // Sesuaikan nama kolom tabel buku jika berbeda (misal judul_buku)
                                        echo "<option value='".$b['id_buku']."'>BK00".$b['id_buku']." - ".$b['judul']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pinjam <span class="required">*</span></label>
                                <!-- Default value otomatis diisi tanggal hari ini -->
                                <input type="date" name="tanggal_pinjam" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Jatuh Tempo <span class="required">*</span></label>
                                <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" class="form-control">
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
                            </tr>
                        </thead>
<tbody>
                            <?php 
                            $no = 1;
                            // Query INNER JOIN yang sudah disesuaikan 100% dengan nama kolom database kamu
                            $query_tampil = "SELECT peminjaman.*, buku.judul_buku AS judul_buku, anggota.nama_anggota AS nama_anggota 
                                             FROM peminjaman 
                                             INNER JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                             INNER JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota 
                                             ORDER BY id_peminjaman DESC";
                            
                            $data_peminjaman = mysqli_query($koneksi, $query_tampil);
                            
                            while($row = mysqli_fetch_array($data_peminjaman)) {
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
                                        echo ($row['tanggal_kembali'] != NULL && $row['tanggal_kembali'] != '0000-00-00') 
                                             ? date('d M Y', strtotime($row['tanggal_kembali'])) 
                                             : '-'; 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') {
                                            echo '<span class="badge text-bg-warning">Dipinjam</span>';
                                        } else {
                                            echo '<span class="badge text-bg-success">Dikembalikan</span>';
                                        }
                                        ?>
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