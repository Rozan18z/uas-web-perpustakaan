<?php
session_start();

// 1. Hubungkan dengan database
include 'koneksi.php';

// 2. KUNCI HALAMAN: Jika belum login, tendang balik ke index.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit();
}

// 3. Query Hitung Statistik Secara Dinamis dari Database Kamu
$hitung_buku = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$data_buku   = mysqli_fetch_assoc($hitung_buku);

$hitung_tersedia = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku WHERE status='tersedia'");
$data_tersedia   = mysqli_fetch_assoc($hitung_tersedia);

$hitung_dipinjam = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_peminjaman='dipinjam'");
$data_dipinjam   = mysqli_fetch_assoc($hitung_dipinjam);

$hitung_anggota = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM anggota WHERE status='aktif'");
$data_anggota   = mysqli_fetch_assoc($hitung_anggota);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Perpustakaan</title>
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
                <a class="nav-link active" href="home.php">Dashboard</a>
                <a class="nav-link" href="buku.php">Buku</a>
                <a class="nav-link" href="anggota.php">Anggota</a>
                <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link" href="logout.php">Log-out</a>
            </nav>
        </div>
    </div>
</header>

        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">Dashboard Perpustakaan</h1>

            <div class="alert alert-success">
                Selamat datang di Sistem Informasi Perpustakaan Sederhana. 
                (Petugas aktif: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>)
            </div>

            <!-- Bagian Angka Statistik Terhubung Otomatis ke DB -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Buku</p>
                            <h2 class="h3"><?php echo $data_buku['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Buku Tersedia</p>
                            <h2 class="h3"><?php echo $data_tersedia['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Dipinjam</p>
                            <h2 class="h3"><?php echo $data_dipinjam['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Anggota Aktif</p>
                            <h2 class="h3"><?php echo $data_anggota['total']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Peminjaman Terbaru Dinamis dengan INNER JOIN Akurat -->
            <div class="card card-box">
                <div class="card-header bg-white">
                    <strong>Peminjaman Terbaru</strong>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Anggota</th>
                                <th>Tanggal Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_terbaru = "SELECT peminjaman.*, buku.judul_buku, anggota.nama_anggota 
                                              FROM peminjaman 
                                              INNER JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                              INNER JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota 
                                              ORDER BY id_peminjaman DESC LIMIT 5";
                            $tampil_terbaru = mysqli_query($koneksi, $query_terbaru);
                            
                            if(mysqli_num_rows($tampil_terbaru) > 0) {
                                while($row = mysqli_fetch_array($tampil_terbaru)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['kode_peminjaman']; ?></td>
                                        <td><?php echo $row['judul_buku']; ?></td>
                                        <td><?php echo $row['nama_anggota']; ?></td>
                                        <td><?php echo date('d F Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                        <td><?php echo date('d F Y', strtotime($row['tanggal_jatuh_tempo'])); ?></td>
                                        <td>
                                            <?php if($row['status_peminjaman'] == 'dipinjam') { ?>
                                                <span class="badge text-bg-warning">Dipinjam</span>
                                            <?php } else { ?>
                                                <span class="badge text-bg-success">Dikembalikan</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada transaksi peminjaman</td></tr>";
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