<?php
session_start();

// 1. Hubungkan dengan database
include 'koneksi.php';

// (OPSIONAL) KUNCI HALAMAN: Jika grup kalian mewajibkan login dulu, hapus tanda '//' di bawah ini
/*
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit();
}
*/

// 2. Query Hitung Statistik Secara Dinamis dari Database
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
    <!-- Memanggil CSS Bootstrap & CSS Kelompok -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- NAVBAR ATAS (Seragam dengan Buku dan Anggota) -->
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

        <!-- KONTEN DASHBOARD UTAMA -->
        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">Dashboard Perpustakaan</h1>

            <div class="alert alert-success">
                Selamat datang di Sistem Informasi Perpustakaan Sederhana. 
                (Petugas aktif: <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></strong>)
            </div>

            <!-- BAGIAN KARTU STATISTIK (Angkanya Berubah Otomatis Sesuai Database) -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-stat border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted fw-semibold mb-1">Total Buku</p>
                            <h2 class="h3 fw-bold text-primary"><?php echo $data_buku['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted fw-semibold mb-1">Buku Tersedia</p>
                            <h2 class="h3 fw-bold text-success"><?php echo $data_tersedia['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted fw-semibold mb-1">Sedang Dipinjam</p>
                            <h2 class="h3 fw-bold text-warning"><?php echo $data_dipinjam['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted fw-semibold mb-1">Anggota Aktif</p>
                            <h2 class="h3 fw-bold text-info"><?php echo $data_anggota['total']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT TRANSAKSI TERBARU (Menggunakan INNER JOIN) -->
            <div class="card card-box border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <strong class="text-dark">Log Peminjaman Terbaru</strong>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kode Pinjam</th>
                                <th>Judul Buku</th>
                                <th>Nama Anggota</th>
                                <th>Tanggal Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mengambil 5 data peminjaman paling baru dengan menggabungkan nama buku & nama anggota
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
                                        <td class="ps-4 fw-semibold"><?php echo $row['kode_peminjaman']; ?></td>
                                        <td><?php echo $row['judul_buku']; ?></td>
                                        <td><?php echo $row['nama_anggota']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['tanggal_jatuh_tempo'])); ?></td>
                                        <td class="pe-4">
                                            <?php if($row['status_peminjaman'] == 'dipinjam') { ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php } else { ?>
                                                <span class="badge bg-success">Dikembalikan</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>Belum ada riwayat transaksi peminjaman.</td></tr>";
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