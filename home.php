<?php
session_start();

// 1. KUNCI HALAMAN: Jika belum login, tendang balik ke index.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit();
}
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

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Buku</p>
                            <h2 class="h3">125</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Buku Tersedia</p>
                            <h2 class="h3">98</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Dipinjam</p>
                            <h2 class="h3">27</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <p class="text-muted mb-1">Anggota Aktif</p>
                            <h2 class="h3">64</h2>
                        </div>
                    </div>
                </div>
            </div>

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
                            <tr>
                                <td>PMJ001</td>
                                <td>Algoritma dan Struktur Data</td>
                                <td>Siti Aminah</td>
                                <td>20 Juni 2026</td>
                                <td>27 Juni 2026</td>
                                <td><span class="badge text-bg-warning">Dipinjam</span></td>
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