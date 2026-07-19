<?php
// 1. Memanggil file koneksi database
include 'koneksi.php';

// 2. Proses logika pendaftaran (PHP)
if (isset($_POST['register'])) {
    // Ambil data dari form dengan aman
    $nama_lengkap   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email          = mysqli_real_escape_string($koneksi, $_POST['email']);
    $username       = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role           = mysqli_real_escape_string($koneksi, $_POST['role']);
    $password       = $_POST['password'];
    $konfirmasi     = $_POST['konfirmasi_password'];
    
    // Validasi kecocokan password
    if ($password !== $konfirmasi) {
        $error_message = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username atau email sudah terdaftar di tabel 'users'
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");

        if (mysqli_num_rows($cek_user) > 0) {
            $error_message = "Waduh, Username atau email sudah digunakan!";
        } else {
            // LOGIKA DIPERBAIKI: Kita tidak me-ngacak passwordnya, biar sinkron dengan index.php
            $query = "INSERT INTO users (nama_lengkap, email, username, role, password)
                      VALUES ('$nama_lengkap', '$email', '$username', '$role', '$password')";

            // Jika berhasil disimpan, munculkan Pop-up dan lempar ke index.php
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Akun berhasil didaftarkan! Silakan login dengan akun barumu.'); window.location='index.php';</script>";
                exit(); // Hentikan proses agar redirect berjalan mulus
            } else {
                $error_message = "Gagal mendaftarkan akun: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Sistem Informasi Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<main class="auth-page">
    <div class="container">
        <div class="row align-items-center justify-content-center g-4">
            <div class="col-lg-5 auth-side">
                <div class="brand-mark mb-3">P</div>
                <h1 class="display-6 fw-bold">Daftarkan Petugas Baru</h1>
                <p class="lead mb-0">Rancangan form pembuatan akun petugas perpustakaan.</p>
            </div>

            <div class="col-md-9 col-lg-6">
                <div class="auth-card">
                    <h2 class="h4 section-title mb-1">Register Akun</h2>
                    <p class="text-muted mb-4">Isi data berikut untuk membuat akun baru.</p>

                    <!-- Tempat memunculkan notifikasi Error / Sukses -->
                    <?php if (isset($error_message)) : ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required minlength="3">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Username <span class="required">*</span></label>
                                <input type="text" name="username" class="form-control" required minlength="4">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role <span class="required">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="">Pilih role</option>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password <span class="required">*</span></label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                                <input type="password" name="konfirmasi_password" class="form-control" required minlength="6">
                            </div>

                            <div class="col-12">
                                <!-- Ditambahkan name="register" agar diproses oleh PHP -->
                                <button type="submit" name="register" class="btn btn-primary w-100">Daftar Akun</button>
                            </div>
                        </div>
                    </form>

                    <p class="text-center mt-4 mb-0">
                        Sudah punya akun? <a href="index.php">Masuk sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>