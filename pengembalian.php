<?php
// 1. Jalankan session check untuk keamanan login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 2. Hubungkan dengan file koneksi
include 'koneksi.php';

// 3. LOGIKA PROSES PENGEMBALIAN BUKU & DENDA
if (isset($_GET['proses_kembali'])) {
    $id_kembali = mysqli_real_escape_string($koneksi, $_GET['proses_kembali']);
    $tgl_sekarang = date('Y-m-d'); 
    
    // Cek dulu tanggal jatuh temponya untuk menghitung denda saat tombol diklik
    $cek_pinjam = mysqli_query($koneksi, "SELECT tanggal_jatuh_tempo FROM peminjaman WHERE id_peminjaman = '$id_kembali'");
    $data_pinjam = mysqli_fetch_assoc($cek_pinjam);
    $tgl_jatuh_tempo = $data_pinjam['tanggal_jatuh_tempo'];
    
    // Hitung selisih hari
    $selisih_hari = floor((strtotime($tgl_sekarang) - strtotime($tgl_jatuh_tempo)) / (60 * 60 * 24));
    $pesan_alert = "";

    if ($selisih_hari > 0) {
        $total_denda = $selisih_hari * 1000; // Tarif denda Rp 1.000 per hari
        // Jika terlambat, tambahkan info tagihan ke pop-up
        $pesan_alert = "Buku berhasil dikembalikan!\\n\\nSTATUS: TERLAMBAT $selisih_hari HARI\\nTOTAL DENDA: Rp " . number_format($total_denda, 0, ',', '.') . "\\n\\nSilakan arahkan anggota untuk membayar denda via CASH ke petugas atau scan QRIS Perpustakaan.";
    } else {
        // Jika tepat waktu
        $pesan_alert = "Mantap! Buku berhasil dikembalikan tepat waktu. Tidak ada denda.";
    }

    // Update data di database: isi tanggal_kembali dengan hari ini
    $proses = mysqli_query($koneksi, "UPDATE peminjaman SET tanggal_kembali = '$tgl_sekarang' WHERE id_peminjaman = '$id_kembali'");

    if ($proses) {
        echo "<script>alert('$pesan_alert'); window.location='pengembalian.php';</script>";
    } else {
        echo "<script>alert('Waduh, gagal memproses pengembalian buku.'); window.location='pengembalian.php';</script>";
    }
    exit();
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengembalian Buku</title>
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
                        <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                        <a class="nav-link active" href="pengembalian.php">Pengembalian</a>
                        <a class="nav-link" href="logout.php">Log-out</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="page-shell px-3 py-4">
            <h1 class="page-title h3 mb-4">Proses Pengembalian & Denda</h1>

            <div class="alert alert-info">
                Halaman khusus untuk memproses buku yang dikembalikan dan mencatat tagihan denda keterlambatan (Rp 1.000 / hari).
            </div>

            <!-- TABEL DATA PENGEMBALIAN -->
            <div class="card card-box">
                <div class="card-header bg-white">
                    <strong>Daftar Sirkulasi Buku</strong>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Nama Anggota</th>
                                <th>Jatuh Tempo</th>
                                <th>Tgl Kembali</th>
                                <th>Tagihan Denda</th> <!-- Kolom Baru -->
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            // Query untuk mengambil data peminjaman
                            $query_tampil = "SELECT peminjaman.*, buku.judul_buku AS judul_buku, anggota.nama_anggota AS nama_anggota 
                                             FROM peminjaman 
                                             INNER JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                             INNER JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota 
                                             ORDER BY id_peminjaman DESC";
                            
                            $data_peminjaman = mysqli_query($koneksi, $query_tampil);
                            
                            while($row = mysqli_fetch_array($data_peminjaman)) {
                                $tgl_jatuh_tempo = strtotime($row['tanggal_jatuh_tempo']);
                                $denda_teks = "-";

                                // ALGORITMA HITUNG DENDA
                                if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') {
                                    // Buku belum kembali, hitung dari hari ini
                                    $tgl_sekarang = strtotime(date('Y-m-d'));
                                    if ($tgl_sekarang > $tgl_jatuh_tempo) {
                                        $selisih_hari = floor(($tgl_sekarang - $tgl_jatuh_tempo) / (60 * 60 * 24));
                                        $denda = $selisih_hari * 1000;
                                        // Tampilkan nominal dan cara bayar
                                        $denda_teks = "<span class='text-danger fw-bold'>Rp " . number_format($denda, 0, ',', '.') . "</span><br><small class='text-muted'>Via Cash / QRIS</small>";
                                    } else {
                                        $denda_teks = "<span class='text-success'>Aman (Rp 0)</span>";
                                    }
                                } else {
                                    // Buku sudah kembali, hitung denda berdasarkan tanggal kembalinya
                                    $tgl_kembali = strtotime($row['tanggal_kembali']);
                                    if ($tgl_kembali > $tgl_jatuh_tempo) {
                                        $selisih_hari = floor(($tgl_kembali - $tgl_jatuh_tempo) / (60 * 60 * 24));
                                        $denda = $selisih_hari * 1000;
                                        $denda_teks = "<span class='text-muted'>Rp " . number_format($denda, 0, ',', '.') . " (Lunas)</span>";
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['kode_peminjaman']; ?></td>
                                    <td><?php echo $row['judul_buku']; ?></td>
                                    <td><?php echo $row['nama_anggota']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['tanggal_jatuh_tempo'])); ?></td>
                                    <td>
                                        <?php 
                                        if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') {
                                            echo '-';
                                        } else {
                                            echo date('d M Y', strtotime($row['tanggal_kembali']));
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $denda_teks; ?></td>
                                    <td>
                                        <?php 
                                        if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') {
                                            if (strtotime(date('Y-m-d')) > $tgl_jatuh_tempo) {
                                                echo '<span class="badge text-bg-danger">Terlambat</span>';
                                            } else {
                                                echo '<span class="badge text-bg-warning">Dipinjam</span>';
                                            }
                                        } else {
                                            echo '<span class="badge text-bg-success">Selesai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if($row['tanggal_kembali'] == NULL || $row['tanggal_kembali'] == '0000-00-00') { 
                                        ?>
                                            <a href="pengembalian.php?proses_kembali=<?php echo $row['id_peminjaman']; ?>" 
                                               class="btn btn-sm btn-success" 
                                               onclick="return confirm('Apakah buku ini sudah diterima dan denda (jika ada) sudah dibayar?')">
                                               Terima Buku
                                            </a>
                                        <?php 
                                        } else { 
                                            echo '<span class="text-success fw-bold">✔ Tuntas</span>';
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