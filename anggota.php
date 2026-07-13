<?php
// 1. Koneksi ke Database & Session Check
include 'koneksi.php';
// session_start(); // Buka tanda komentar ini jika login session kalian diaktifkan

$status_edit = false;
$kode_anggota = ""; 
$nama_anggota = ""; 
$jenis_kelamin = ""; 
$telepon = ""; 
$alamat = "";

// 2. LOGIKA TOMBOL EDIT (Mengambil data lama untuk ditaruh di form)
if (isset($_GET['edit'])) {
    $status_edit = true;
    $id_anggota = mysqli_real_escape_string($koneksi, $_GET['edit']);
    
    $ambil_data = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id_anggota = '$id_anggota'");
    if (mysqli_num_rows($ambil_data) > 0) {
        $data_lama = mysqli_fetch_array($ambil_data);
        $kode_anggota  = $data_lama['kode_anggota'];
        $nama_anggota  = $data_lama['nama_anggota'];
        $jenis_kelamin = $data_lama['jenis_kelamin'];
        $telepon       = $data_lama['telepon'];
        $alamat        = $data_lama['alamat'];
    }
}

// 3. LOGIKA TOMBOL HAPUS
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query_hapus = mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota = '$id_hapus'");
    
    if ($query_hapus) {
        echo "<script>alert('Data anggota berhasil dihapus!'); window.location='anggota.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
}

// 4. LOGIKA TOMBOL SIMPAN (Bisa berupa Tambah Baru ATAU Simpan Perubahan Edit)
if (isset($_POST['simpan'])) {
    $kode_anggota  = mysqli_real_escape_string($koneksi, $_POST['kode_anggota']);
    $nama_anggota  = mysqli_real_escape_string($koneksi, $_POST['nama_anggota']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $telepon       = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    if ($status_edit) {
        // Jika dalam mode edit, eksekusi UPDATE
        $id_anggota = mysqli_real_escape_string($koneksi, $_GET['edit']);
        $query_simpan = "UPDATE anggota SET 
                         kode_anggota = '$kode_anggota', 
                         nama_anggota = '$nama_anggota', 
                         jenis_kelamin = '$jenis_kelamin', 
                         telepon = '$telepon', 
                         alamat = '$alamat' 
                         WHERE id_anggota = '$id_anggota'";
    } else {
        // Jika mode biasa, eksekusi INSERT (Tambah baru)
        $query_simpan = "INSERT INTO anggota (kode_anggota, nama_anggota, jenis_kelamin, telepon, alamat) 
                         VALUES ('$kode_anggota', '$nama_anggota', '$jenis_kelamin', '$telepon', '$alamat')";
    }
    
    if (mysqli_query($koneksi, $query_simpan)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='anggota.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Data Anggota</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>CRUD Data Anggota</h2>
    <hr>

    <!-- FORM TAMBAH / EDIT ANGGOTA -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            <?= $status_edit ? "Form Edit Anggota" : "Form Tambah Anggota"; ?>
        </div>
        <div class="card-body">
            <form action="" method="post" onsubmit="return validasiFormAnggota()">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Anggota *</label>
                        <input type="text" name="kode_anggota" class="form-control" value="<?=$kode_anggota;?>" placeholder="Contoh: AG001" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota *</label>
                        <input type="text" name="nama_anggota" class="form-control" value="<?=$nama_anggota;?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" <?=($jenis_kelamin == 'Laki-laki') ? 'selected' : '';?>>Laki-laki</option>
                            <option value="Perempuan" <?=($jenis_kelamin == 'Perempuan') ? 'selected' : '';?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?=$telepon;?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?=$alamat;?></textarea>
                </div>

                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                <a href="anggota.php" class="btn btn-secondary">Reset / Batal</a>
            </form>
        </div>
    </div>

    <!-- TABEL DATA ANGGOTA -->
    <div class="card">
        <div class="card-header fw-bold">Daftar Anggota Perpustakaan</div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Anggota</th>
                        <th>Jenis Kelamin</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $q = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id_anggota DESC");
                    while ($r = mysqli_fetch_array($q)) {
                    ?>
                    <tr>
                        <td><?=$no++;?></td>
                        <td><code><?=$r['kode_anggota'];?></code></td>
                        <td><?=$r['nama_anggota'];?></td>
                        <td><?=$r['jenis_kelamin'];?></td>
                        <td><?=$r['telepon'];?></td>
                        <td><?=$r['alamat'];?></td>
                        <td>
                            <a href="?edit=<?=$r['id_anggota'];?>" class="btn btn-sm btn-warning text-dark fw-bold">Edit</a>
                            <a href="?hapus=<?=$r['id_anggota'];?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JAVASCRIPT VALIDASI -->
<script>
function validasiFormAnggota() {
    let kode = document.getElementsByName('kode_anggota')[0].value.trim();
    if(kode.length < 3) {
        alert('Kode Anggota minimal harus 3 karakter!');
        return false;
    }
    return true;
}
</script>

</body>
</html>